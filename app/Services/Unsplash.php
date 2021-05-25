<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Unsplash
{
    public function search ($keyword, $lang)
    {
        Log::debug('Searching ' . $keyword . ' (' . $lang . ') in Unsplash...');
        $response = Http::withHeaders([
                'Accept-Version' => 'v1',
                'Authorization' => 'Client-ID ' . config('services.unsplash.access-key'),
            ])
            ->get('https://api.unsplash.com/search/photos', [
                'query' => $keyword,
                'lang' => $lang,
            ])->json();

        if (isset($response['results']) && is_array($response['results']) && !empty($response['results'])) {
            return $response['results'];
        }

        return null;
    }
}
