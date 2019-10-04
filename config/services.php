<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),  // Идентификатор клиента
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),  // Секретный ключ
        'redirect' => env('GOOGLE_CALLBACK_URL'),  // Ссылка на перенаправление при удачной авторизации
    ],
    'github' => [
        'client_id' => env('GITHUB_KEY'),  // Идентификатор клиента
        'client_secret' => env('GITHUB_SECRET'),  // Секретный ключ
        'redirect' => env('GITHUB_REDIRECT_URI'),  // Ссылка на перенаправление при удачной авторизации
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_KEY'),  // Идентификатор клиента
        'client_secret' => env('FACEBOOK_SECRET'),  // Секретный ключ
        'redirect' => env('FACEBOOK_REDIRECT_URI'),  // Ссылка на перенаправление при удачной авторизации
    ],
    'twitter' => [
        'client_id' => env('TWITTER_KEY'),  // Идентификатор клиента
        'client_secret' => env('TWITTER_SECRET'),  // Секретный ключ
        'redirect' => env('TWITTER_REDIRECT_URI'),  // Ссылка на перенаправление при удачной авторизации
    ],
    'yandex' => [
        'client_id' => env('YANDEX_KEY'),  // Идентификатор клиента
        'client_secret' => env('YANDEX_SECRET'),  // Секретный ключ
        'redirect' => env('YANDEX_REDIRECT_URI'),  // Ссылка на перенаправление при удачной авторизации
    ],
    'vkontakte' => [
        'client_id' => env('VKONTAKTE_KEY'),  // Идентификатор клиента
        'client_secret' => env('VKONTAKTE_SECRET'),  // Секретный ключ
        'redirect' => env('VKONTAKTE_REDIRECT_URI'),  // Ссылка на перенаправление при удачной авторизации
    ],

];
