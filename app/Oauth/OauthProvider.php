<?php

namespace App\Oauth;

use App\Exceptions\UnknownOauthAction;
use App\Models\Service;
use App\Models\ToolAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class OauthProvider {
    protected $name;

    protected function __construct ($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public abstract function getAvailableActions ();

    public function getActions (Service $service)
    {
        $actions = ToolAction::where([
            'provider' => $this->id,
            'service_id' => $service->id,
        ])->first();

        if (!$actions) {
            return null;
        }

        return array_intersect_key($this->getAvailableActions(), array_flip($actions->actions));
    }

    public function perform ($action, Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $action = 'do' . ucfirst(Str::camel($action));
        if (method_exists($this, $action)) {
            return call_user_func([$this, $action], $request);
        }

        throw new UnknownOauthAction('Unknown action', 500);
    }
}
