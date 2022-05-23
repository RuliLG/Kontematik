<?php

namespace App\Http\Livewire;

use App\Exceptions\AlreadyGenerating;
use App\Exceptions\LimitReachedException;
use App\Exceptions\RateLimitException;
use App\Exceptions\UnsafePrompt;
use App\Models\Service;
use App\Services\Copywriter as ServicesCopywriter;
use App\Services\Integrations;
use App\Services\Translation;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Copywriter extends Component
{
    public $service;
    public $data = [];
    public $responses = [];
    public $result = null;
    public $indexable = true;
    public $rating = 0;
    public $languages = [];
    public $language = 'auto';
    public $languageMap = [];
    public $integrationToken = null;

    protected $listeners = ['selectedToken' => 'userDidSelectToken'];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->service->load('fields', 'prompts');
        foreach ($this->service->fields as $field) {
            if (!isset($this->data[$field->name])) {
                $this->data[$field->name] = Session::get($field->name, '');
            }
        }

        $this->languageMap = [
            'es' => (new Translation())->getOrTranslate('Spanish'),
            'en' => (new Translation())->getOrTranslate('English'),
            'de' => (new Translation())->getOrTranslate('German'),
            'it' => (new Translation())->getOrTranslate('Italian'),
            'fr' => (new Translation())->getOrTranslate('French'),
            'pt' => (new Translation())->getOrTranslate('Portuguese'),
        ];

        $this->language = Session::get('prompt_language', 'auto');
        $this->languages = $this->service->prompts->map(function ($prompt) {
            $lang = isset($this->languageMap[$prompt->language_code]) ? $prompt->language_code : $this->language;
            return [
                'code' => $lang,
                'name' => country_flag_emoji(mb_strtoupper($lang)) . ' ' . $this->languageMap[$lang],
            ];
        })
            ->sortBy('name')
            ->toArray();


        $integrations = json_decode(json_encode((new Integrations())->active()), true);
        if (!empty($integrations)) {
            $this->integrationToken = $integrations[0]['token']['token'];
        }
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

        $copywriter->setOrigin('website');
        $copywriter->setOriginUrl(url('/'));

        try {
            $response = $copywriter->generate($this->service, $this->data, $this->language);
        } catch (LimitReachedException $e) {
            logger($e);
            $this->addError('limit_reached', true);
            return;
        } catch (UnsafePrompt $e) {
            logger($e);
            $this->addError('unsafe_prompt', true);
            return;
        } catch (RateLimitException $e) {
            logger($e);
            $this->addError('rate_limit', $e->getMessage());
            return;
        } catch (AlreadyGenerating $e) {
            logger($e);
            $this->addError('already_generating', true);
            return;
        }

        $this->responses = $response['responses'];
        $this->result = $response['result'];
        $this->indexable = $response['result']->is_indexable;
        $this->rating = intval($this->result->rating);
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

    public function userDidSelectToken($token)
    {
        $this->integrationToken = $token;
    }

}
