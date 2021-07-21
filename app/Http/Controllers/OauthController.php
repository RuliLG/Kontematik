<?php

namespace App\Http\Controllers;

use App\Exceptions\UnknownOauthAction;
use App\Models\Service;
use App\Oauth\OauthFactory;
use Illuminate\Http\Request;

class OauthController extends Controller
{
    public function perform (Request $request)
    {
        $request->validate([
            'provider' => 'required',
            'action' => 'required',
        ]);

        try {
            $oauth = OauthFactory::from($request->provider);
            return $oauth->perform($request->action, $request);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() > 0 ? $e->getCode() : 500);
        }
    }

    public function getActions (Service $tool, Request $request)
    {
        try {
            $oauth = OauthFactory::from($request->provider);
            return response()->json([
                'actions' => $oauth->getActions($tool)
            ]);
        } catch (UnknownOauthAction $e) {
            return response()->json([
                'actions' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode() > 0 ? $e->getCode() : 500);
        }
    }
}
