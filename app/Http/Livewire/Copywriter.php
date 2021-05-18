<?php

namespace App\Http\Livewire;

use App\Models\Service;
use App\Services\Gpt3;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Copywriter extends Component
{
    public $service;
    public $data = [];
    public $responses = [];
    public $loading = false;

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
        $this->loading = true;
        try {
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
        } catch (\Exception $e) {
        }

        $this->loading = false;
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
