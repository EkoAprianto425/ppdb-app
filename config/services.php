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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | VA Inquiry API Authentication (HMAC-SHA256 / BI SNAP Standard)
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk autentikasi API inquiry Virtual Account.
    | Gunakan credential ini saat memanggil endpoint:
    |   GET /api/va/{va_number}
    |   GET|POST /api/va/inquiry
    |
    */
    'va_inquiry' => [
        'client_key'          => env('VA_INQUIRY_CLIENT_KEY'),
        'client_secret'       => env('VA_INQUIRY_CLIENT_SECRET'),
        'timestamp_tolerance' => env('VA_INQUIRY_TIMESTAMP_TOLERANCE', 5),
    ],

];
