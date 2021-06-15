<?php

namespace App\Http\Controllers;

use App\Models\OauthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HubspotController extends Controller
{
    public function render(Request $request)
    {
        $token = null;
        if ($request->get('code')) {
            $token = Http::asForm()->post('https://api.hubapi.com/oauth/v1/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.hubspot.client'),
                'client_secret' => config('services.hubspot.secret'),
                'redirect_uri' => route('hubspot'),
                'code' => $request->get('code'),
            ])->json();

            if (!isset($token['access_token'])) {
                $token = false;
            }
        }

        if ($token) {
            $oauth = new OauthToken;
            $oauth->user_id = auth()->id();
            $oauth->provider = 'hubspot';
            $oauth->token = $token['access_token'];
            $oauth->refresh_token = $token['refresh_token'];
            $oauth->expires_at = now()->addSeconds($token['expires_in']);
            $oauth->save();
        }

        return view('integrations.hubspot.oauth', [
            'token' => $token,
        ]);
    }

    public function oauth(Request $request)
    {
        $scopes = join('%20', ['content']);
        $authUrl = 'https://app.hubspot.com/oauth/authorize?client_id=' . config('services.hubspot.client') . '&scope=' . $scopes . '&redirect_uri=' . urlencode(route('hubspot'));
        return redirect($authUrl);
    }
}
