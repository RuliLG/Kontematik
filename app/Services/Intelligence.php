<?php

namespace App\Services;

use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;

class Intelligence
{
    public function detectLanguage ($text)
    {
        $text = substr($text, 0, 256);
        return GoogleTranslateFacade::detectLanguage($text)['language_code'];
    }

    public function translate ($text, $toLang, $fromLang = null)
    {
        return GoogleTranslateFacade::justTranslate($text, $toLang, $fromLang ?? $this->detectLanguage($text));
    }

    public function getKeywords ($text)
    {
        $api = new TextRankFacade();
        $stopWords = new English();
        $api->setStopWords($stopWords);
        return $api->getOnlyKeyWords($text);
    }
}
