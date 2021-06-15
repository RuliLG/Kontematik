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
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Copywriter {
    public function generate(Service $tool, $data, $language = 'auto')
    {
        $responses = [];
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

        $result = new Result;
        $result->user_id = Auth::id();
        $result->service_id = $tool->id;
        $result->language_code = $language_;
        $result->prompt = $this->prompt($tool, $data, $language);
        $result->params = json_encode($data);

        // Validate prompt against OpenAI
        if (!$this->promptIsValid($result->prompt)) {
            $result->is_nsfw = true;
            $result->save();
            throw new UnsafePrompt();
        }

        try {
            $result->user_tokens = Tokenizer::count(join('', array_values($data)));
            $result->total_tokens = Tokenizer::count($result->prompt);

            $response = (new Gpt3)
                ->engine($tool->gpt3_engine)
                ->temperature($tool->gpt3_temperature)
                ->tokens($tool->gpt3_tokens)
                ->bestOf($tool->gpt3_best_of)
                ->take($tool->gpt3_n)
                ->completion($this->prompt($tool, $data, $language));

            $responses = array_map(function ($r) {
                return Str::finish(Str::beforeLast(trim($r['text']), '.'), '.');
            }, $response);
            $result->response = json_encode($responses);
            $result->save();

            Notification::route('slack', config('services.slack.notification'))
                ->notify(new TextGenerated($result));
        } catch (\Exception $e) {
            Notification::route('slack', config('services.slack.notification'))
                ->notify(new ErrorNotification($e, [
                    'Tool' => $tool->name,
                    'Prompt' => $this->prompt($tool, $data, $language),
                    'Language' => $language_,
                    'UserId' => Auth::id(),
                ]));
        }

        return [
            'responses' => $responses,
            'result' => $result,
            'language' => $language_,
        ];
    }

    public function share(Result $result)
    {
        if (!$result->webflow_share_uuid) {
            $result->webflow_share_uuid = Str::uuid();
            (new Webflow)->publish($result);
        }
    }

    public function toggleIndexation(Result $result)
    {
        (new Webflow)->toggleIndexation($result);
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
        return (new Gpt3)->isSafe($prompt);
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
}
