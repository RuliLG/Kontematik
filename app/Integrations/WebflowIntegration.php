<?php

namespace App\Integrations;

class WebflowIntegration extends Integration {
    public function id()
    {
        return 'webflow';
    }

    public function name()
    {
        return 'Webflow';
    }

    public function image()
    {
        return asset('images/providers/webflow.png');
    }

    public function description()
    {
        return 'Connect Kontematik to Webflow and let us suggest new posts for your blog';
    }

    public function isAvailable()
    {
        return false;
    }
}
