<?php

namespace App\Oauth;

use App\Exceptions\UnknownOauthAction;

class OauthFactory {
    public static function from ($id)
    {
        switch ($id) {
            case 'hubspot':
                return new HubspotProvider;
            default:
                throw new UnknownOauthAction('Unknown provider', 500);
        }
    }
}
