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
    public function store(LoginRequest $request): JsonResponse {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified. Please check your inbox for the verification link.'], 403);
        }

        // Assuming you want to return the authenticated user's data:
        return response()->json([
            'user'    => Auth::user(),
            'message' => 'Login successful',
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse {
        // Logout the user using the 'web' guard
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->flush(); // Clear all session data

            // Remove the session cookie manually without regenerating the session
            $cookieDomain = '.' . $request->getHost();

            return response()->json(['message' => 'Logged Out'], 200)
                             ->withCookie(cookie('surveycat_session', '', -1, '/', $cookieDomain, false, true));
        }

        return response()->json(['message' => 'No authenticated guard found'], 400);
    }
}
