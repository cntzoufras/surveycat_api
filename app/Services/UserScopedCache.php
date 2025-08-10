<?php

namespace App\Services;

use Closure;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserScopedCache
{
    public function keyFrom(Request $request): string
    {
        $userId = Auth::id() ?? 0;
        $sessionId = $request->session()->getId() ?? 'guest';
        $route = $request->route()?->getName() ?? $request->path();
        $method = $request->getMethod();
        $qs = $request->getQueryString() ?? '';
        $hash = hash('sha256', $qs);

        return sprintf(
            'http_cache:%s:%s:%s:%s:%s:%s',
            app()->environment(),
            $userId,
            $sessionId,
            $method,
            $route,
            $hash
        );
    }

    public function tagsFor(int|string|null $userId, string $sessionId): array
    {
        return ["user:{$userId}", "session:{$sessionId}"];
    }

    public function remember(Request $request, int $ttl, Closure $resolver)
    {
        $userId = Auth::id() ?? 0;
        $sessionId = $request->session()->getId() ?? 'guest';
        $key = $this->keyFrom($request);
        $tags = $this->tagsFor($userId, $sessionId);

        return Cache::tags($tags)->remember($key, $ttl, $resolver);
    }

    public function flushByTags(array $tags): void
    {
        Cache::tags($tags)->flush();
    }
}
