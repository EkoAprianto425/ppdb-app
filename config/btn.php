<?php   
return [
    'id' => env('BTN_ID', 'PPDBALHASRA'),
    'key' => env('BTN_KEY'),
    'secret' => env('BTN_SECRET'),
    'base_url' => env('BTN_BASE_URL', 'https://vabtn-dev.btn.co.id/v1/ppdbalhasra'),
    'kode_institusi' => env('BTN_KODE_INSTITUSI', '4842'),
    'va_length' => env('BTN_VA_LENGTH', 17),
];