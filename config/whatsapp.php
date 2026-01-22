<?php

return [
    'token' => env('WHATSAPP_TOKEN'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'api_version' => env('WHATSAPP_API_VERSION', 'v19.0'),

    'template' => [
        'name' => env('WHATSAPP_TEMPLATE_NAME', null),
        'lang' => env('WHATSAPP_TEMPLATE_LANG', 'es_MX'),
    ],
];
