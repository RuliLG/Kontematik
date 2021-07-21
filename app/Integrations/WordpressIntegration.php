<?php

namespace App\Integrations;

class WordpressIntegration extends Integration {
    public function id()
    {
        return 'wordpress';
    }

    public function name()
    {
        return 'Wordpress';
    }

    public function image()
    {
        return asset('images/providers/wordpress.png');
    }

    public function icon()
    {
        return asset('images/providers/wordpress_thumbnail.png');
    }

    public function description()
    {
        return 'Connect Kontematik to Wordpress and let us suggest new posts for your blog';
    }

    public function isAvailable()
    {
        return false;
    }
}
