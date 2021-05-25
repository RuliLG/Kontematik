<?php

namespace App\Services;

use SilverDiamond\SilverDiamond;

class Intelligence
{
    public function detectLanguage ($text)
    {
        $text = substr($text, 0, 256);
        return $this->silver()->language($text);
    }

    public function getKeywords ($text)
    {
        return $this->silver()->textRankKeywords($text);
    }

    private function silver ()
    {
        return new SilverDiamond(config('services.silver-diamond.token'));
    }
}
