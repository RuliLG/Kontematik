<?php

namespace App\Services;

use App\Integrations\IntegrationFactory;
use App\Models\OauthToken;

class Integrations {
    public function active ()
    {
        $tokens = OauthToken::where('user_id', auth()->id())
            ->where('expires_at', '>=', now())
            ->get();
        $integrations = $tokens->map(function ($token) {
            return IntegrationFactory::from($token->provider)
                ->with($token);
        });

        return $integrations;
    }
}
