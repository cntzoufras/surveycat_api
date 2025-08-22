<?php

namespace App\Http\Middleware;

use App\Services\UserScopedCache;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CacheUserResponse
{
    public function __construct(private UserScopedCache $cache) {}

    public function handle(Request $request, Closure $next)
    {
        if (strtoupper($request->method()) !== 'GET') {
            return $next($request);
        }

        $ttl = (int) config('http_cache.ttl', 3600);

        // Build key and tags using the service helpers
        $key = $this->cache->keyFrom($request);
        $tags = $this->cache->tagsFromRequest($request);

        // Attempt cache hit
        $cached = Cache::tags($tags)->get($key);
        if (is_array($cached) && isset($cached['__http_cache'])) {
            return new JsonResponse($cached['data'], $cached['status'], $cached['headers']);
        }

        // Miss: execute and possibly store
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            $payload = [
                '__http_cache' => true,
                'status' => $response->getStatusCode(),
                'headers' => $response->headers->all(),
                'data' => $response->getData(true),
            ];
            Cache::tags($tags)->put($key, $payload, $ttl);
        }

        return $response;
    }
}
