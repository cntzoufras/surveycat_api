<?php

namespace App\Listeners;

use App\Services\UserScopedCache;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

class ClearSessionCacheOnLogout
{
    public function __construct(private UserScopedCache $cache) {}

    public function handle(Logout $event): void
    {
        $userId = optional($event->user)->id ?? 0;
        if ($userId) {
            // In API context, sessions may not be started; use user-level invalidation.
            $this->cache->flushByTags(["user:{$userId}"]);
        }
    }
}
