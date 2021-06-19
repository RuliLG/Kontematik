<?php

namespace App\Oauth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HubspotProvider extends OauthProvider {

    public function __construct ()
    {
        parent::__construct('hubspot', 'Hubspot');
        $this->endpoint = 'https://api.hubapi.com';
    }

    public function getAvailableActions()
    {
        return [
            OauthAction::CREATE_PAGE => 'Create new page',
            OauthAction::CREATE_PAGES => 'Create pillar pages',
        ];
    }

    public function doPage (Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $request->token,
        ])->post($this->endpoint . '/content/api/v2/pages', [
            'name' => $request->get('text'),
            'slug' => Str::slug($request->get('text')),
        ]);

        $response->throw();

        return response()->json(['success' => true, 'response' => $response->json()]);
    }

    public function doPages (Request $request)
    {
        $pages = collect(explode("\n", $request->get('text')))
            ->map(function ($raw) {
                $index = strpos($raw, ':');
                $type = $index !== false && $index >= 0 ? substr($raw, 0, $index) : 'Pillar';
                $type = mb_strtolower(trim($type, "-\t\n\r\0\x0B "));
                $type = Str::startsWith($type, 'pilar') ? 'topic' : 'subtopic';
                $title = $index !== false && $index >= 0 ? trim(substr($raw, $index + 1)) : trim($raw);
                return [
                    'type' => $type,
                    'title' => $title,
                    'raw' => $raw,
                ];
            });

        foreach ($pages as $page) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $request->token,
            ])->post($this->endpoint . '/content/api/v2/pages', [
                'name' => $page['raw'],
                'slug' => Str::slug($page['title']),
            ]);
        }

        $response->throw();

        return response()->json(['success' => true]);
    }
}
