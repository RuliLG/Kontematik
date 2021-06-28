<?php

namespace App\Integrations;

use App\Exceptions\UnknownOauthAction;

class IntegrationFactory {
    public static function from ($name)
    {
        switch ($name) {
            case 'hubspot':
                return new HubspotIntegration();
            case 'wordpress':
                return new WordpressIntegration();
            case 'webflow':
                return new WebflowIntegration();
            case 'wix':
                return new WixIntegration();
            default:
                throw new UnknownOauthAction('Unknown integration', 500);
        }
    }
}
