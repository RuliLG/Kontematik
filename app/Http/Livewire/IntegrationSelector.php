<?php

namespace App\Http\Livewire;

use App\Services\Integrations;
use Livewire\Component;

class IntegrationSelector extends Component
{
    public $integrations;
    public $selectedToken = null;

    public function mount()
    {
        $this->integrations = json_decode(json_encode((new Integrations())->active()), true);
        if (!empty($this->integrations)) {
            $this->selectedToken = $this->integrations[0]['token']['token'];
        }
    }

    public function getSelectedProperty()
    {
        return array_filter($this->integrations, function ($integration) {
            $token = $integration['token']['token'];
            return $token == $this->selectedToken;
        })[0];
    }

    public function selectToken ($token)
    {
        $this->selectedToken = $token;
        $this->notifyChanges();
    }

    public function render()
    {
        return view('livewire.integration-selector');
    }

    public function notifyChanges()
    {
        $this->emitUp('selectedToken', $this->selectedToken);
    }
}
