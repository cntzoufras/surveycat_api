<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {

    private $auth;

    public function __construct(AuthService $service) {
        $this->auth = $service;
    }

    /**
     * Attempt a login using a username/password combination
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse {
        $this->validate($request, [
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $user = $this->auth->authenticateByCredentials(
            $request->input('email'),
            $request->input('password')
        );

        return response()->json([
            'token' => $token,
        ]);
    }

    public function logout(Request $request) {
        $this->auth->logout($request->bearerToken());
        response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Refresh the credentials of a party, using their refresh token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function refresh(Request $request) {
        $this->validate($request, [
            'refresh_token' => 'required|string',
        ]);
        $party = $this->auth->getUserFromRefreshToken($request->input('refresh_token'));
        $token = $this->auth->getToken($party, true);

        return response()->json([
            'token'         => $token,
            'refresh_token' => $this->auth->getRefreshToken($party, $token),
        ]);
    }

    public function forgotPassword(Request $request) {
        $this->validate($request, [
            'username' => 'required',
        ]);
        $this->auth->requestPasswordReset($request->input('username'));
        return response()->json([
            'message' => trans('passwords.reset_requested'),
        ]);
    }

    public function resetPassword(Request $request) {
        $this->validate($request, [
            'token'                 => 'required',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        $this->auth->resetPassword($request->input('token'), $request->input('password'));
        return response()->json([
            'message' => trans('passwords.reset'),
        ]);
    }

    public function register(Request $request): JsonResponse {
        $validated = $request->validate([
            'username'  => 'required|string|max:255|unique:users,username',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|string|min:8',
            'cPassword' => 'required|same:password',
        ]);
        try {
            $user = $this->auth->register($validated);

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'message'     => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
