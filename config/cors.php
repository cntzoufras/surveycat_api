<?php

return [

    'paths'                    => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'auth/*'],
    'allowed_methods'          => ['*'],
//    'allowed_origins' => ['*'],
    'allowed_origins'          => [
//        'http://localhost:3000',
//        'http://surveycat.test:3000',
//        'http://surveycat.test',
//        'http://localhost',
//        'https://my-live-survey-app.com',
        '*',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,
];