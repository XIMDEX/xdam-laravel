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

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

    'tika' => [
        'mode' => env('TIKA_MODE', 'server'),
        'server' => [
            'host' => env('TIKA_SERVER_HOST', 'localhost'),
            'port' => env('TIKA_SERVER_PORT', 9998),
        ],
        'app' =>  env('TIKA_PATH', 'tika-app.jar'),
    ],

    'resource' => [
        'preview' => 'xdam.resource.preview'
    ]
];