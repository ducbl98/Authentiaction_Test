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

    'google' => [
        'client_id' => '268087522148-qa6csavv15hl60v6t9e5aso39q7reim3.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost:8000/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => '1026159161298544',
        'client_secret' => 'bb778a2741cfd3d5e56459ea6078174f',
        'redirect' => 'https://cc23-2405-4802-257-8640-39ce-6c5e-2cd9-f7c2.ngrok.io/auth/facebook/callback',
    ],

];
