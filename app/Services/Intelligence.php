<?php

namespace App\Services;

use SilverDiamond\SilverDiamond;

class Intelligence
{
    public function detectLanguage ($text)
    {
        $text = substr($text, 0, 256);
        $silver = new SilverDiamond(config('services.silver-diamond.token'));
        return $silver->language($text);
    }
}
