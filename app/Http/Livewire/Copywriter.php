<?php

namespace App\Http\Livewire;

use App\Exceptions\LimitReachedException;
use App\Exceptions\UnsafePrompt;
use App\Models\Result;
use App\Models\SavedResult;
use App\Models\Service;
use App\Notifications\ErrorNotification;
use App\Notifications\SavedResult as NotificationsSavedResult;
use App\Notifications\TextGenerated;
use App\Services\Copywriter as ServicesCopywriter;
use App\Services\Gpt3;
use App\Services\Intelligence;
use App\Services\Tokenizer;
use App\Services\Webflow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
    public $language = 'auto';
    public $languageMap = [
        'es' => 'Spanish',
        'en' => 'English',
        'de' => 'German',
        'it' => 'Italian',
        'fr' => 'French',
    ];

    private $language_ = null;

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->service->load('fields', 'prompts');
        foreach ($this->service->fields as $field) {
            if (!isset($this->data[$field->name])) {
                $this->data[$field->name] = Session::get($field->name, '');
            }
        }

        $this->language = Session::get('prompt_language', 'auto');
        $this->languages = $this->service->prompts->map(function ($prompt) {
            $lang = isset($this->languageMap[$prompt->language_code]) ? $prompt->language_code : $this->language;
            return [
                'code' => $lang,
                'name' => country_flag(mb_strtoupper($lang)) . ' ' . $this->languageMap[$lang],
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
        $this->responses = [];
        $this->result = null;

        $copywriter = new ServicesCopywriter;
        $this->validate($copywriter->validationRules($this->service, 'data.'));

        try {
            $response = $copywriter->generate($this->service, $this->data, $this->language);
        } catch (LimitReachedException $e) {
            $this->addError('limit_reached', true);
            return;
        } catch (UnsafePrompt $e) {
            $this->addError('unsafe_prompt', true);
            return;
        }

        $this->language_ = $response['language'];
        $this->responses = $response['responses'];
        $this->result = $response['result'];
        $this->indexable = $response['result']->is_indexable;
        $this->rating = intval($this->result->rating);
        $this->saved = [];
    }

    public function saveGeneratedText ($idx)
    {
        $text = $this->result->response[$idx];
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
        (new ServicesCopywriter)->share($this->result);
    }

    public function toggleIndexation()
    {
        (new ServicesCopywriter)->toggleIndexation($this->result);
        $this->indexable = $this->result->is_indexable;
    }

    public function rate ($rating)
    {
        $rating = min(5, max(1, $rating));
        $this->result->rating = $rating;
        $this->result->save();
        $this->rating = $this->result->rating;
    }

}
