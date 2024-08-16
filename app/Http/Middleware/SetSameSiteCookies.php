<?php

namespace App\Http\Middleware;

use Closure;

class SetSameSiteCookies {

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);

        if ($response instanceof \Illuminate\Http\Response) {
            $response->headers->setCookie(cookie('XSRF-TOKEN', $request->session()->token(), 120, '/', null, true, true, false, 'None'));
        }

        return $response;
    }
}
