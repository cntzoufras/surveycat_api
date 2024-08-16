<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller {

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Already verified.'], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!'], 202);
    }
}
