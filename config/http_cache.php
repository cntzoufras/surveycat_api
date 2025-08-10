<?php

return [
    // Default TTL in seconds for user-scoped HTTP response cache
    'ttl' => env('HTTP_CACHE_TTL', 3600),
];
