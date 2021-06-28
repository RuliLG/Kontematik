<?php

namespace App\Http\Controllers;

use App\Integrations\IntegrationFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function render()
    {
        return view('settings', [
            'user' => Auth::user()
        ]);
    }
    public function renderPassword()
    {
        return view('password-settings');
    }

    public function renderNichePreferences()
    {
        return view('niche-preferences');
    }

    public function renderIntegrations(Request $request)
    {
        if ($request->get('type')) {
            try {
                $integration = IntegrationFactory::from($request->get('type'));
                return $integration->save($request);
            } catch (\Exception $e) {
                Log::error($e);
                return redirect(route('profile.integrations'));
            }
        }

        return view('integrations');
    }
}
