<?php

namespace App\Integrations;

class WixIntegration extends Integration {
    public function id()
    {
        return 'wix';
    }

    public function name()
    {
        return 'Wix';
    }

    public function image()
    {
        return asset('images/providers/wix.png');
    }

    public function description()
    {
        return 'Connect Kontematik to Wix and let us suggest new posts for your blog';
    }

    public function isAvailable()
    {
        return false;
    }
}
