<?php

namespace App\Integrations;

use App\Models\OauthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HubspotIntegration extends Integration {
    public function id()
    {
        return 'hubspot';
    }

    public function name()
    {
        return 'Hubspot';
    }

    public function image()
    {
        return asset('images/providers/hubspot.png');
    }

    public function description()
    {
        return 'Connect Kontematik to Hubspot and let us suggest new blog posts for your marketing strategy';
    }

    public function isAvailable()
    {
        return true;
    }

    public function details()
    {
        if (!$this->token) {
            return null;
        }

        if ($this->details) {
            return $this->details;
        }

        $response = Http::get('https://api.hubapi.com/oauth/v1/access-tokens/' . $this->token->token);
        $response->throw();
        $response = $response->json();
        $this->details = (new AccountDetails($response))
            ->map('user', AccountDetails::USER)
            ->map('hub_id', AccountDetails::SITE_ID)
            ->map('hub_domain', AccountDetails::SITE_NAME);
        return $this->details;
    }

    public function renew ()
    {
        if (!$this->token) {
            return null;
        }

        $token = Http::asForm()->post('https://api.hubapi.com/oauth/v1/token', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.hubspot.client'),
            'client_secret' => config('services.hubspot.secret'),
            'refresh_token' => $this->token->refresh_token,
        ])->json();

        if (isset($token['access_token'])) {
            $this->storeRenewedToken($token['access_token'], $token['refresh_token'], now()->addSeconds($token['expires_in']));
        }
    }

    public function link()
    {
        $scopes = join('%20', ['content']);
        $authUrl = 'https://app.hubspot.com/oauth/authorize?client_id=' . config('services.hubspot.client') . '&scope=' . $scopes . '&redirect_uri=' . urlencode(route('profile.integrations', ['type' => $this->id()]));
        return $authUrl;
    }

    public function save(Request $request)
    {
        if ($request->get('code')) {
            $token = Http::asForm()->post('https://api.hubapi.com/oauth/v1/token', [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.hubspot.client'),
                'client_secret' => config('services.hubspot.secret'),
                'redirect_uri' => route('profile.integrations', ['type' => $this->id()]),
                'code' => $request->get('code'),
            ])->json();

            if (!isset($token['access_token'])) {
                $token = false;
            }
        }

        if ($token) {
            $oauth = new OauthToken();
            $oauth->user_id = auth()->id();
            $oauth->provider = 'hubspot';
            $oauth->token = $token['access_token'];
            $oauth->refresh_token = $token['refresh_token'];
            $oauth->expires_at = now()->addSeconds($token['expires_in']);
            $this->saveIfNotRepeated($oauth);
        }

        return redirect(route('profile.integrations'));
    }
}
