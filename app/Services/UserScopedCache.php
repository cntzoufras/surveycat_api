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

    /**
     * Build cache tags based on request context (user/session + resource + survey).
     */
    public function tagsFromRequest(Request $request): array
    {
        $userId = Auth::id() ?? 0;
        $sessionId = $request->session()->getId() ?? 'guest';

        $tags = $this->tagsFor($userId, $sessionId);

        // Resource tag: first path segment (e.g., survey-submissions, respondents)
        $path = trim($request->path(), '/');
        $firstSegment = explode('/', $path)[0] ?? '';
        if ($firstSegment !== '') {
            $tags[] = "resource:{$firstSegment}";
        }

        // Survey scoping: look for survey id in common places
        $surveyId = $request->query('survey_id')
            ?? $request->input('survey_id')
            ?? $request->route('survey')
            ?? $request->route('surveyId')
            ?? $request->route('id');
        if (!empty($surveyId)) {
            $tags[] = "survey:{$surveyId}";
        }

        return $tags;
    }

    public function remember(Request $request, int $ttl, Closure $resolver)
    {
        $key = $this->keyFrom($request);
        $tags = $this->tagsFromRequest($request);

        return Cache::tags($tags)->remember($key, $ttl, $resolver);
    }

    public function flushByTags(array $tags): void
    {
        Cache::tags($tags)->flush();
    }
}
