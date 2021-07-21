<?php

namespace App\Http\Livewire;

use App\Integrations\IntegrationFactory;
use App\Models\OauthToken;
use App\Models\SavedResult;
use App\Models\ToolAction;
use App\Notifications\SavedResult as NotificationsSavedResult;
use App\Oauth\OauthFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Component;

class Result extends Component
{
    public $service;
    public $result;
    public $response;
    public $isSaved = false;
    public $id_;
    public $i;
    public $integrationToken = null;
    public $actions = [];
    public $icon = null;

    public function mount()
    {
        $this->id_ = (string) Str::uuid();
        $this->getActions();
    }

    public function render()
    {
        return view('livewire.result');
    }

    public function save()
    {
        if ($this->isSaved) {
            SavedResult::where([
                'service_id' => $this->service->id,
                'result_id' => $this->result->id,
                'user_id' => Auth::id(),
                'output' => $this->response,
            ])->delete();
            $this->isSaved = false;
            return;
        }

        $saved = new SavedResult;
        $saved->service_id = $this->service->id;
        $saved->result_id = $this->result->id;
        $saved->user_id = Auth::id();
        $saved->params = is_string($this->result->params) ? $this->result->params : json_encode($this->result->params);
        $saved->output = $this->response;
        $saved->save();

        $this->isSaved = true;

        Notification::route('slack', config('services.slack.notification'))
            ->notify(new NotificationsSavedResult($this->response, Auth::user(), $this->service));
    }

    public function perform($action)
    {
        $integration = $this->getIntegration();
        if (!$integration) {
            return;
        }

        $toolActions = ToolAction::where([
            'service_id' => $this->service->id,
            'provider' => $integration->provider,
        ])->first();
        if (!$toolActions) {
            return;
        }

        $provider = OauthFactory::from($integration->provider);
        $provider->perform($action, new \Illuminate\Http\Request([
            'text' => $this->response,
            'provider' => $integration->provider,
        ]));
    }

    private function getIntegration()
    {
        return OauthToken::where('expires_at', '>=', now())
            ->where('should_renew', true)
            ->where('token', $this->integrationToken)
            ->first();
    }

    private function getActions()
    {
        $integration = $this->getIntegration();
        if (!$integration) {
            return;
        }

        $toolActions = ToolAction::where([
            'service_id' => $this->service->id,
            'provider' => $integration->provider,
        ])->first();
        if (!$toolActions) {
            return;
        }

        $provider = OauthFactory::from($integration->provider);
        $this->actions = $provider->getActions($this->service);
        $this->icon = IntegrationFactory::from($integration->provider)->icon();
    }
}
