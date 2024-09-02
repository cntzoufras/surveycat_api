<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

class VerifyEmailController extends Controller {

    public function __invoke(Request $request) {
        $user = User::findOrFail($request->route('id'));
        $frontendUrl = config('app.frontend_url');

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect($frontendUrl . '/verification-error');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($frontendUrl . '/verification-already');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($frontendUrl . '/verification-success');
    }

}

