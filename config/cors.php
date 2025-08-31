<?php

$envOrigins = array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', ''))));

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'auth/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => array_values(array_unique(array_merge(
        ['http://surveycat.test:3000'],
        $envOrigins 
    ))),
    'allowed_origins_patterns' => [],
    'allowed_headers'          => ['*'],
    'exposed_headers'          => [],
    'max_age'                  => 0,
    'supports_credentials'     => true,
];