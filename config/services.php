<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'openai' => [
        'gpt3' => env('GPT3_TOKEN'),
        'gpt3_content_filter_threshold' => env('GPT3_CONTENT_FILTER_THRESHOLD', 1),
    ],

    'slack' => [
        'notification' => env('SLACK_WEBHOOK'),
    ],

    'webflow' => [
        'token' => env('WEBFLOW_TOKEN'),
    ],

    'silver-diamond' => [
        'token' => env('SILVERDIAMOND_TOKEN'),
    ],

    'unsplash' => [
        'access-key' => env('UNSPLASH_ACCESS_KEY'),
        'secret-key' => env('UNSPLASH_SECRET_KEY'),
    ],

    'mailjet' => [
        'key' => env('MAILJET_APIKEY'),
        'secret' => env('MAILJET_APISECRET'),
    ]

];
