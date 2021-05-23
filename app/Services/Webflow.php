<?php

namespace App\Services;

use App\Models\Result;
use App\Models\ServiceField;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class Webflow {
    public function publish (Result $result)
    {
        $ids = [];
        foreach ($result->response as $response) {
            $uuid = Str::uuid();
            $response = $this->http()->post('https://api.webflow.com/collections/60a91deec2681864b279c39f/items?live=true', [
                'fields' => [
                    'name' => $uuid,
                    'slug' => $uuid,
                    '_archived' => false,
                    '_draft' => false,
                    'output' => $response,
                ]
            ])->throw();

            $ids[] = $response->json()['_id'];
        }

        $fields = [
            'tool-name' => $result->service->name,
            'language' => $result->language_code,
            'name' => 'AI Copywriting ' . $result->webflow_share_uuid,
            'slug' => $result->webflow_share_uuid,
            '_archived' => false,
            '_draft' => false,
            'outputs' => $ids,
            'robots' => 'index',
        ];

        $i = 1;
        $serviceFields = ServiceField::where('service_id', $result->service_id)
            ->get()
            ->keyBy('name');
        foreach ($result->params as $key => $value) {
            if (!empty(trim($value))) {
                $fields['field-' . $i] = $serviceFields[$key]->label;
                $fields['field-' . $i . '-value'] = $value;
                $i += 1;
            }
        }

        $response = $this->http()->post('https://api.webflow.com/collections/60a90f28de611b70276d3115/items?live=true', [
            'fields' => $fields,
        ]);

        $response->throw();
        $response = $response->json();

        $result->webflow_id = $response['_id'];
        $result->save();
    }

    public function toggleIndexation(Result $result)
    {
        if (!$result->webflow_id) {
            return;
        }

        $response = $this->http()->patch('https://api.webflow.com/collections/60a90f28de611b70276d3115/items/' . $result->webflow_id . '?live=true', [
            'fields' => [
                'robots' => $result->is_indexable ? 'noindex' : 'index',
            ],
        ]);

        $response->throw();

        $result->is_indexable = !$result->is_indexable;
        $result->save();
    }

    private function http()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.webflow.token'),
            'Accept-Version' => '1.0.0',
        ]);
    }
}
