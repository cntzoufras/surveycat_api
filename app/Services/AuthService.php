<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Events\PasswordResetRequested;

class AuthService {

    protected UserRepository $users;

    public function __construct(UserRepository $users) {
        $this->users = $users;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register($params): User {
        $this->validateUsername($params['username']);
        $this->validateEmail($params['email']);
        $this->validateRegexPassword($params['password']);

        $user = new User();
        $user->username = $params['username'];
        $user->email = $params['email'];
        $user->password = Hash::make($params['password']);
        $user->role = 'registered';

        DB::transaction(function () use ($user) {
            $this->users->save($user);
        });
        return $user;
    }

    public function requestPasswordReset($username): bool {
        $user = $this->users->findByUsername($username);
        if (!$user) return false;

        $user->password_reset_token = Str::random(64);
        $user->password_reset_at = Carbon::now();

        $token = Str::random(64);
        $user->password_reset_token = $token;
        $user->password_reset_at = Carbon::now();

        DB::transaction(function () use ($user, $token) {
            $this->users->save($user);
            event(new PasswordResetRequested($user, $token));
        });

        return true;
    }

    /**
     * Resets user password
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeCredentials(User $user, array $params): void {
        if (isset($params['username'])) {
            $this->validateUsername($params['username'], $user);
            $user->username = $params['username'];
        }
        if (isset($params['password'])) {
            $this->validateRegexPassword($params['password']);
            $user->password = Hash::make($params['password']);
        }
        $user->save();
    }

    /**
     * Logs out user, deletes the token
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function logout($token): void {
        $user = Auth::user();

        if (!$user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        $tokenDeleted = $user->tokens()->where('id', $token->id)->delete();

        if (!$tokenDeleted) {
            throw new AuthenticationException('Invalid token.');
        }
    }

    /**
     * Validates username does not already exist
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateUsername($username, $existing_user = null): void {
        if (!is_null($existing_user) && $username == $existing_user->username) return;

        $exists = User::query()
                      ->where('username', $username)
                      ->exists();
        if ($exists) {
            throw ValidationException::withMessages(['username' => 'The username already exists.']);
        }
    }

    /**
     * Validates password matches the required criteria
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateRegexPassword($input): void {
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($pattern, $input)) {
            throw ValidationException::withMessages([
                'password' => ['Password must be at least 8 characters long,include at least one lower & one uppercase letter, one number, and one special character (@, $, !, %, *, ?, &).'],
                // Use an array for errors
            ]);
        }
    }

    /**
     * Validates email does not already exist
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validateEmail($email): void {
        if (!is_null(null) && $email == null->email) return;

        $exists = User::query()
                      ->where('email', $email)
                      ->exists();
        if ($exists) {
            throw ValidationException::withMessages(['email' => 'The email already exists.']);
        }
    }
}
