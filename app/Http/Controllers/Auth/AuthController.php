<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {


    protected AuthService $auth_service;

    public function __construct(AuthService $auth_service) {
        $this->auth_service = $auth_service;
    }

    /**
     * Attempt a login using a username/password combination *OK
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse {
        $this->validate($request, [
            'email'       => 'required|string|email|exists:users,email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        $credentials = $request->only('email', 'password'); // Extract credentials from request

        // Attempt to authenticate the user using Laravel's built-in authentication
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException();
        } else {

            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Email not verified. Please check your inbox for the verification link.'], 403); // 403 Forbidden
            }

            return response()->json([
                'message' => 'Login successful',
                'user'    => $user,
                'token'   => $token,
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @throws \Exception
     */
    public function user(Request $request): mixed {
        try {
            $user = Auth::user();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch user'], 500);
        }
    }

    /**
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function logout(Request $request) {
        if ($request->user()) {
            try {
                $this->auth_service->logout($request->user()->currentAccessToken());
                return response()->json(['message' => 'Token Revoked'], 204); // No content response
            } catch (AuthenticationException $e) {
                return response()->json(['message' => $e->getMessage()], 401);
            }
        }

        return response()->json(['message' => 'Not Authenticated'], 401);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgotPassword(Request $request): JsonResponse {
        $this->validate($request, [
            'username' => 'required|string|exists:users,username',
        ]);
        $result = $this->auth_service->requestPasswordReset($request->input('username'));
        if ($result) {
            return response()->json([
                'message' => trans('passwords.reset_requested'),
            ], 200);
        } else {
            return response()->json([
                'message' => trans('passwords.user_not_found'),
            ], 404);
        }
    }

    public function resetPassword(Request $request): JsonResponse {
        $this->validate($request, [
            'token'                 => 'required',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        $this->auth_service->resetPassword($request->input('token'), $request->input('password'));
        return response()->json([
            'message' => trans('passwords.reset'),
        ]);
    }

}
