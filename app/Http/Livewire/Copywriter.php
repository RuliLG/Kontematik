<?php

namespace App\Http\Livewire;

use App\Models\Result;
use App\Models\Service;
use App\Services\Gpt3;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Copywriter extends Component
{
    public $service;
    public $data = [];
    public $responses = [];

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->service->load('fields');
        foreach ($this->service->fields as $field) {
            if (!isset($this->data[$field->name])) {
                $this->data[$field->name] = '';
            }
        }
    }

    public function render()
    {
        return view('livewire.copywriter');
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
            $result->language_code = 'es'; // TODO
            $result->prompt = $this->prompt();

            $response = (new Gpt3)
                ->davinci()
                ->temperature($this->service->gpt3_temperature)
                ->tokens($this->service->gpt3_tokens)
                ->bestOf($this->service->gpt3_best_of)
                ->take($this->service->gpt3_n)
                ->completion($this->prompt());

            $this->responses = array_map(function ($r) {
                return explode("\n", trim($r['text']))[0];
            }, $response);
            $result->response = json_encode($this->responses);
            $result->save();
        } catch (\Exception $e) {
        }
    }

    private function prompt()
    {
        $prompt = $this->service->prompts[0]->raw_prompt;
        foreach ($this->data as $key => $value) {
            $prompt = str_replace('{' . $key . '}', $value, $prompt);
        }

        return $prompt;
    }

}
