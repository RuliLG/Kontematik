<?php

namespace App\Http\Livewire;

use App\Models\Result;
use App\Models\SavedResult;
use App\Models\Service;
use App\Notifications\ErrorNotification;
use App\Notifications\SavedResult as NotificationsSavedResult;
use App\Notifications\TextGenerated;
use App\Services\Gpt3;
use App\Services\Intelligence;
use App\Services\Webflow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Copywriter extends Component
{
    public $service;
    public $data = [];
    public $responses = [];
    public $saved = [];
    public $result = null;
    public $indexable = true;
    public $rating = 0;
    public $languages = [];
    public $language = 'es';
    public $languageMap = [
        'es' => 'Spanish',
        'en' => 'English',
        'de' => 'German',
        'it' => 'Italian',
        'fr' => 'French',
    ];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->service->load('fields', 'prompts');
        foreach ($this->service->fields as $field) {
            if (!isset($this->data[$field->name])) {
                $this->data[$field->name] = Session::get($field->name, '');
            }
        }

        $this->language = Session::get('prompt_language', 'es');
        $this->languages = $this->service->prompts->map(function ($prompt) {
            $lang = isset($this->languageMap[$prompt->language_code]) ? $prompt->language_code : $this->language;
            return [
                'code' => $lang,
                'name' => country_flag($lang) . ' ' . $this->languageMap[$lang],
            ];
        })
            ->sortBy('name')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.copywriter');
    }

    public function updatedData ($value, $key)
    {
        Session::put($key, $value);
    }

    public function updatedLanguage ($value)
    {
        Session::put('prompt_language', $value);
    }

    public function getDefaultFieldsProperty()
    {
        return $this->service->fields->filter(function ($field) {
            return $field->field_location === 'default';
        });
    }

    public function generate()
    {
        try {
            $result = new Result;
            $result->user_id = Auth::id();
            $result->service_id = $this->service->id;
            $result->language_code = $this->language;
            $result->prompt = $this->prompt();
            $result->params = json_encode($this->data);

            $response = (new Gpt3)
                ->davinci()
                ->temperature($this->service->gpt3_temperature)
                ->tokens($this->service->gpt3_tokens)
                ->bestOf($this->service->gpt3_best_of)
                ->take($this->service->gpt3_n)
                ->completion($this->prompt());

            $this->responses = array_map(function ($r) {
                return trim($r['text']);
            }, $response);
            $result->response = json_encode($this->responses);
            $result->save();

            $this->result = $result;
            $this->indexable = $result->is_indexable;
            $this->rating = intval($this->result->rating);
            $this->saved = [];

            Notification::route('slack', config('services.slack.notification'))
                ->notify(new TextGenerated($result));
        } catch (\Exception $e) {
            Notification::route('slack', config('services.slack.notification'))
                ->notify(new ErrorNotification($e, [
                    'Tool' => $this->service->name,
                    'Prompt' => $this->prompt(),
                    'Language' => $this->language,
                    'UserId' => Auth::id(),
                ]));
        }
    }

    public function saveGeneratedText ($text)
    {
        if (isset($this->saved[$text])) {
            SavedResult::where([
                'service_id' => $this->service->id,
                'result_id' => $this->result->id,
                'user_id' => Auth::id(),
                'output' => $text,
            ])->delete();
            unset($this->saved[$text]);
            return;
        }

        $saved = new SavedResult;
        $saved->service_id = $this->service->id;
        $saved->result_id = $this->result->id;
        $saved->user_id = Auth::id();
        $saved->params = is_string($this->result->params) ? $this->result->params : json_encode($this->result->params);
        $saved->output = $text;
        $saved->save();

        $this->saved[$text] = true;

        Notification::route('slack', config('services.slack.notification'))
            ->notify(new NotificationsSavedResult($text, Auth::user(), $this->service));
    }

    public function share()
    {
        if (!$this->result->webflow_share_uuid) {
            $this->result->webflow_share_uuid = Str::uuid();
            (new Webflow)->publish($this->result);
        }
    }

    public function toggleIndexation()
    {
        (new Webflow)->toggleIndexation($this->result);
        $this->indexable = $this->result->is_indexable;
    }

    public function rate ($rating)
    {
        $rating = min(5, max(1, $rating));
        $this->result->rating = $rating;
        $this->result->save();
        $this->rating = $this->result->rating;
    }

    private function prompt()
    {
        $prompt = $this->service->prompts->filter(function ($prompt) {
            return $prompt->language_code === $this->language;
        })->values();
        $prompt = empty($prompt) ? $this->service->prompts[0] : $prompt[0];
        $prompt = trim($prompt->raw_prompt);
        foreach ($this->data as $key => $value) {
            $prompt = str_replace('{' . $key . '}', $value, $prompt);
        }

        return $prompt;
    }

}
