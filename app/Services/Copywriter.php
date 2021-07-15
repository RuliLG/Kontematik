<?php

namespace App\Services;

use App\Exceptions\LimitReachedException;
use App\Exceptions\UnsafePrompt;
use App\Models\Result;
use App\Models\Service;
use App\Notifications\ErrorNotification;
use App\Notifications\TextGenerated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class Copywriter {
    public function generate(Service $tool, $data, $language = 'auto')
    {
        $tool->load('prompts');

        if (!Gate::allows('can-generate')) {
            throw new LimitReachedException();
        }

        $language_ = null;

        if ($language === 'auto') {
            $text = join("\n", array_values($data));
            $lang = (new Intelligence())->detectLanguage($text);
            $langDoesNotExist = $tool->prompts->filter(function ($prompt) use ($lang) {
                return $lang === $prompt->language_code;
            })->isEmpty();

            if ($langDoesNotExist) {
                $lang = $tool->prompts[0]->language_code;
            }

            $language_ = $lang;
        } else {
            $language_ = $language;
        }

        $result = null;
        try {
            switch ($tool->generation_type) {
                case 'per_line':
                    $result = $this->perLineGeneration($tool, $language_, $data);
                    break;
                default:
                    $result = $this->singleGeneration($tool, $language_, $data);
                    break;
            }

            Notification::route('slack', config('services.slack.notification'))
                ->notify(new TextGenerated($result['result']));
        } catch (\Exception $e) {
            Notification::route('slack', config('services.slack.notification'))
                ->notify(new ErrorNotification($e, [
                    'Tool' => $tool->name,
                    'Prompt' => $this->prompt($tool, $data, $language_),
                    'Language' => $language_,
                    'UserId' => Auth::id(),
                    'Message' => $e->getMessage(),
                ]));

            Log::error('Error generating text for tool '. $tool->name . ': ' . $e->getMessage());

            throw $e;
        }

        return $result;
    }

    public function share(Result $result)
    {
        if (!$result->webflow_share_uuid) {
            $result->webflow_share_uuid = Str::uuid();
            (new Webflow())->publish($result);
        }
    }

    public function toggleIndexation(Result $result)
    {
        (new Webflow())->toggleIndexation($result);
    }

    private function prompt(Service $tool, $data, $lang)
    {
        $prompt = $tool->prompts->filter(function ($prompt) use ($lang) {
            return $prompt->language_code === $lang;
        })->values();

        $prompt = $prompt->isEmpty() ? $tool->prompts[0] : $prompt[0];
        $prompt = trim($prompt->raw_prompt);
        foreach ($data as $key => $value) {
            $prompt = str_replace('{' . $key . '}', $value, $prompt);
        }

        return $prompt;
    }

    public function promptIsValid($prompt)
    {
        return (new Gpt3())->isSafe($prompt);
    }

    public function validationRules(Service $tool, $prefix = '')
    {
        $rules = [];
        foreach ($tool->fields as $field) {
            $rule = ['string'];
            $rule[] = $field->is_required ? 'required' : 'sometimes|nullable';
            $rule[] = $field->max_length > 0 ? 'max:' . $field->max_length : null;
            $rule = array_filter($rule);
            $rules[$prefix . $field->name] = join('|', $rule);
        }

        return $rules;
    }

    private function singleGeneration(Service $tool, $language, $data)
    {
        $result = new Result();
        $result->user_id = Auth::id();
        $result->service_id = $tool->id;
        $result->language_code = $language;
        $result->prompt = $this->prompt($tool, $data, $language);
        $result->params = json_encode($data);

        // Validate prompt against OpenAI
        if (!$this->promptIsValid($result->prompt)) {
            $result->is_nsfw = true;
            $result->save();
            throw new UnsafePrompt();
        }

        $result->user_tokens = Tokenizer::count(join('', array_values($data)));
        $result->total_tokens = Tokenizer::count($result->prompt);

        // Calculate the number of tokens of the prompt examples
        $prompt = $tool->prompts->filter(function ($prompt) use ($language) {
            return $prompt->language_code === $language;
        })->values();
        $rawPrompt = $prompt->isEmpty() ? $tool->prompts[0] : $prompt[0];
        $rawPrompt = trim($rawPrompt->raw_prompt);
        $promptTokens = max(0, Tokenizer::count($rawPrompt));

        $response = (new Gpt3())
            ->engine($tool->gpt3_engine)
            ->temperature($tool->gpt3_temperature)
            ->tokens($tool->gpt3_tokens + $promptTokens)
            ->bestOf($tool->gpt3_best_of)
            ->take($tool->gpt3_n)
            ->completion($this->prompt($tool, $data, $language));

        $responses = array_map(function ($r) {
            return Str::finish(Str::beforeLast(trim($r['text']), '.'), '.');
        }, $response);
        $result->response = json_encode($responses);
        $result->save();

        return [
            'responses' => $responses,
            'result' => $result,
            'language' => $language,
        ];
    }

    private function perLineGeneration(Service $tool, $language, $data)
    {
        if (!isset($data[$tool->per_line_generation_field_name])) {
            throw new \Exception('Invalid data');
        }

        $lines = collect(explode("\n", $data[$tool->per_line_generation_field_name]))
            ->map(function ($line) {
                return trim($line);
            })
            ->filter();

        // Cortamos por el máximo de líneas a generar
        if ($tool->per_line_max_lines > 0) {
            $lines = $lines->slice(0, $tool->per_line_max_lines);
        }

        // Comprobamos cada posible input a ver si es válido
        $unsafe = false;
        $prompts = [];
        foreach ($lines as $line) {
            $partial = array_merge([], $data);
            $partial[$tool->per_line_generation_field_name] = $line;
            $prompt = $this->prompt($tool, $partial, $language);
            $prompts[] = $prompt;
            if (!$this->promptIsValid($prompt)) {
                $unsafe = true;
                break;
            }
        }

        $result = new Result();
        $result->user_id = Auth::id();
        $result->service_id = $tool->id;
        $result->language_code = $language;
        $result->prompt = join('--------------', $prompts);
        $result->params = json_encode($data);

        if ($unsafe) {
            $result->is_nsfw = true;
            $result->save();
            throw new UnsafePrompt();
        }

        // Generamos los párrafos para cada línea
        $result->user_tokens = Tokenizer::count(join('', array_values($data)));
        $totalTokens = 0;
        $responses = [];

        // TODO: Max generations per minute

        foreach ($lines as $line) {
            $partial = array_merge([], $data);
            $partial[$tool->per_line_generation_field_name] = $line;
            $prompt = $this->prompt($tool, $partial, $language);
            $totalTokens += Tokenizer::count($prompt);

            $response = (new Gpt3())
                ->engine($tool->gpt3_engine)
                ->temperature($tool->gpt3_temperature)
                ->tokens($tool->gpt3_tokens)
                ->bestOf($tool->gpt3_best_of)
                ->take($tool->gpt3_n)
                ->completion($prompt);

            $response = array_map(function ($r) {
                return Str::finish(Str::beforeLast(trim($r['text']), '.'), '.');
            }, $response);

            foreach ($response as $i => $response) {
                if (!isset($responses[$i])) {
                    $responses[$i] = [];
                    foreach ($partial as $key => $value) {
                        if ($key === $tool->per_line_generation_field_name) {
                            continue;
                        }

                        $responses[$i][] = $value;
                    }
                }

                $responses[$i][] = $line . "\n" . $response;
            }
        }

        $responses = array_map(function ($response) {
            return join("\n\n", $response);
        }, $responses);

        $result->total_tokens = $totalTokens;
        $result->response = json_encode($responses);
        $result->save();

        return [
            'responses' => $responses,
            'result' => $result,
            'language' => $language,
        ];
    }
}
