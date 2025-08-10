<?php

namespace App\Http\Middleware;

use App\Services\UserScopedCache;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class InvalidateUserCacheOnWrite
{
    public function __construct(private UserScopedCache $cache) {}

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Invalidate only on non-GET methods and on successful 2xx responses
        if (strtoupper($request->method()) !== 'GET') {
            $status = $response->getStatusCode();
            if ($status >= 200 && $status < 300) {
                $userId = Auth::id();
                if ($userId) {
                    $this->cache->flushByTags(["user:{$userId}"]);
                }
            }
        }

        return $response;
    }
}
