<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller {

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): Response {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse {

        // Determine the current guard being used
        $currentGuard = null;

        // Loop through all defined guards and check which one the user is authenticated with
        foreach (config('auth.guards') as $guard => $guardConfig) {
            if (Auth::guard($guard)->check()) {
                $currentGuard = $guard;
                break;
            }
        }

        // Return the current guard for debugging purposes
        if ($currentGuard) {
            return response()->json(['guard' => $currentGuard, 'message' => 'Testing guard'], 200);
        } else {
            return response()->json(['message' => 'No authenticated guard found'], 400);
        }

        // Check that the guard being used is 'web'
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Logged Out'], 200);
        }

        return response()->json(['message' => 'Logout failed, user not authenticated with web guard'], 400);
    }
}
