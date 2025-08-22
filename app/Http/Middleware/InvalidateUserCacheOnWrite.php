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
                // 1) Always flush resource/survey scoped tags so all user caches are updated
                $path = trim($request->path(), '/');
                $firstSegment = explode('/', $path)[0] ?? '';
                if ($firstSegment !== '') {
                    $this->cache->flushByTags(["resource:{$firstSegment}"]);
                }
                $surveyId = $request->query('survey_id')
                    ?? $request->input('survey_id')
                    ?? $request->route('survey')
                    ?? $request->route('surveyId')
                    ?? $request->route('id');
                if (!empty($surveyId)) {
                    $this->cache->flushByTags(["survey:{$surveyId}"]);
                }

                // 2) Additionally flush the authenticated user's tag (if any)
                $userId = Auth::id();
                if ($userId) {
                    $this->cache->flushByTags(["user:{$userId}"]);
                }
            }
        }

        return $response;
    }
}
