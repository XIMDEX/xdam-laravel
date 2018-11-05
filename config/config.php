<?php

return [
    'enable_solr' => env('XDAM_HAS_SOLR', true),

    'user_token_filed' => 'api_token',

    /*
    |--------------------------------------------------------------------------
    | Tika configs
    |--------------------------------------------------------------------------
    |
    |
    */

    'has_tika' => env('TIKA_ENABLED', true),

    'tika' => [
        'mode' => env('TIKA_MODE', 'server'),
        'server' => [
            'host' => env('TIKA_SERVER_HOST', 'localhost'),
            'port' => env('TIKA_SERVER_PORT', 9998),
        ],
        'app' =>  env('TIKA_PATH', 'tika-app.jar'),
    ]
];
