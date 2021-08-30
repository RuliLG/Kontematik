<?php

namespace App\Services;

use App\Models\Translation as ModelsTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Translation {
    public function get($key, $lang = null)
    {
        $cacheKey = Str::slug($key, '_');
        if (!$lang) {
            $lang = session()->get('hl') ?? 'en';
        }

        if ($lang === 'en') {
            return $key;
        }

        if (Cache::get('translation_' . $cacheKey)) {
            return Cache::get('translation_' . $cacheKey);
        }

        $translation = ModelsTranslation::where([
            'original' => $key,
            'lang' => $lang,
        ])->first();

        if ($translation) {
            Cache::put('translation_' . $cacheKey, $translation->translation, now()->addMinute());
            return $translation->translation;
        }

        return null;
    }

    public function getOrTranslate($key, $lang = null)
    {
        $cacheKey = Str::slug($key, '_');
        if (!$lang) {
            $lang = session()->get('hl') ?? 'en';
        }

        $translation = $this->get($key, $lang);
        if (!$translation) {
            $newText = (new Intelligence())->translate($key, $lang, 'en');
            $translation = new ModelsTranslation();
            $translation->original = $key;
            $translation->lang = $lang;
            $translation->translation = $newText;
            $translation->save();

            Cache::put('translation_' . $cacheKey, $translation->translation, now()->addMinute());
            return $translation->translation;
        }

        return $translation;
    }
}
