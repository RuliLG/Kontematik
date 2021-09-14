<?php

namespace App\Oauth;

use App\Services\Intelligence;
use App\Services\Unsplash;
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
            OauthAction::CREATE_BLOG_POST => 'Create blog post',
        ];
    }

    public function doBlogPost (Request $request)
    {
        $blogs = collect($this->getBlogs($request));
        $language = (new Intelligence)->detectLanguage($request->get('text'));
        $languageBlogs = $blogs->filter(function ($blog) use ($language) {
            return $blog['language'] === $language;
        });
        $blog = $languageBlogs->isEmpty() ? $blogs[0] : $languageBlogs[0];
        $title = explode("\n", $request->get('text'))[0];
        $text = substr($request->get('text'), strlen($title));

        $images = (new Unsplash)->search($title, $language);
        $imageUrl = $images ? $images[0]['urls']['full'] : null;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken($request),
        ])->post($this->endpoint . '/content/api/v2/blog-posts', [
            'name' => $title,
            'slug' => Str::slug($title),
            'post_body' => $text,
            'content_group_id' => $blog['id'],
            'featured_image' => $imageUrl,
        ]);

        $response->throw();

        return response()->json(['success' => true]);
    }

    public function doPage (Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken($request),
        ])->post($this->endpoint . '/content/api/v2/pages', [
            'name' => $request->get('text'),
            'slug' => Str::slug($request->get('text')),
        ]);

        $response->throw();

        return response()->json(['success' => true]);
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
                'Authorization' => 'Bearer ' . $this->getToken($request),
            ])->post($this->endpoint . '/content/api/v2/pages', [
                'name' => $page['raw'],
                'slug' => Str::slug($page['title']),
            ]);
        }

        $response->throw();

        return response()->json(['success' => true]);
    }

    private function getBlogs (Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getToken($request),
        ])->get($this->endpoint . '/content/api/v2/blogs');
        $response->throw();
        $response = $response->json();

        if (!isset($response['objects']) || empty($response['objects'])) {
            throw new \Exception('There are no blogs');
        }

        return $response['objects'];
    }
}
