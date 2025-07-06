<?php

namespace App\Repositories;

use App\Models\Survey\Survey;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserRepository
{

    public function findByUsername(string $username): ?User
    {
        return User::query()->where('username', $username)->first();
    }

    public function findByEmail($email): User
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $email)
            ->firstOrFail();
        return $user;
    }

    public function findById($id)
    {
        return User::query()->where('id', $id)->first();
    }

    public function findByResetToken($token)
    {
        return User::where('password_reset_token', $token)->first();
    }

    public function getIfExist($user)
    {
        return User::query()->find($user);
    }

    public function resolveModel($users)
    {
        if ($users instanceof User) {
            return $users;
        }
        return User::query()->findOrFail($users);
    }

    public function update(User $user, array $params)
    {
        return DB::transaction(function () use ($params, $user) {
            $user->fill($params);
            $user->save();
            return $user;
        });
    }

    public function save(User $user): void
    {
        $user->save();
    }

    /**
     * Store a new avatar and update the user model.
     *
     * @param User $user
     * @param UploadedFile $file
     * @return User
     */
    public function updateAvatar(User $user, UploadedFile $file): User
    {
        // Store the new avatar in 'storage/app/public/avatars'
        $path = $file->store('avatars', 'public');

        // Update the avatar path on the user model
        $user->avatar = $path;
        $user->save();

        return $user;
    }

}
