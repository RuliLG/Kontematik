<?php

namespace App\Http\Livewire\Settings;

use App\Integrations\HubspotIntegration;
use App\Integrations\WebflowIntegration;
use App\Integrations\WixIntegration;
use App\Integrations\WordpressIntegration;
use App\Models\OauthToken;
use App\Services\Integrations as ServicesIntegrations;
use Livewire\Component;

class Integrations extends Component
{
    public $integrations = [];
    public $activeIntegrations = [];

    public function mount()
    {
        $this->init();
    }

    public function render()
    {
        return view('livewire.settings.integrations');
    }

    public function destroy ($id)
    {
        OauthToken::where([
            'user_id' => auth()->id(),
            'id' => $id,
        ])->delete();

        $this->init();
    }

    public function init()
    {
        $this->integrations = [
            new HubspotIntegration(),
            new WordpressIntegration(),
            new WixIntegration(),
            new WebflowIntegration(),
        ];

        $this->activeIntegrations = (new ServicesIntegrations())->active();
    }
}
