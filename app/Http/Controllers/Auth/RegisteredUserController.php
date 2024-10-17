<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller {

    protected AuthService $auth_service;

    public function __construct(AuthService $auth_service) {
        $this->auth_service = $auth_service;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse {

        $requestData = $request->all();
        $requestData['password_confirmation'] = $request->input('passwordConfirmation');

        $validated = Validator::make($requestData, [
            'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/u'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        try {
            $user = $this->auth_service->register($validated);

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            event(new Registered($user));
            event(new UserRegistered($user));

            return response()->json([
                'message'     => 'Successfully created user!',
                'accessToken' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) { // Catch other general exceptions
            \Log::error('Registration failed: ' . $e->getMessage()); // Log the error for debugging
            return response()->json([
                'message' => 'Registration failed. Please try again later.',
            ], 500);
        }
    }
}
