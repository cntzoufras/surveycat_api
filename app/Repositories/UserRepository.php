<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {

    public function findByUsername(string $username): ?User {
        return User::query()->where('username', $username)->first();
    }

    public function findByEmail($email): User {
        /** @var User $user */
        $user = User::query()
                    ->where('email', $email)
                    ->firstOrFail();
        return $user;
    }

    public function findById($id) {
        return User::query()->where('id', $id)->first();
    }

    public function findByResetToken($token) {
        return User::where('password_reset_token', $token)->first();
    }

    public function save(User $user): void {
        $user->save();
    }

}
