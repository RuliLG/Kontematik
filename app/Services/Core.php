<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Core {
    /**
     * Returns the number of tokens from a given prompt
     *
     * @param string $prompt
     * @return int
     */
    public function tokens ($prompt)
    {
        $response = Http::withHeaders([
                'X-Api-Key' => config('services.core.token'),
            ])->post(config('services.core.url') . '/tokenizer', [
                'prompt' => $prompt,
            ]);

        $response->throw();
        $response = $response->json();
        return $response['tokens'];
    }
}
