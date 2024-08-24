<?php

return [

    'paths'                    => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods'          => ['*'],
//    'allowed_origins' => ['*'],
    'allowed_origins'          => [
        'http://localhost:3000',
        'http://surveycat.test:3000',
        'http://surveycat.test',
        'http://localhost',
        'https://snf-893977.vm.okeanos.grnet.gr',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,
];