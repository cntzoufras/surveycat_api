<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class UserService
{

    protected UserRepository $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function show($params)
    {
        return $this->user_repository->getIfExist($params);
    }

    public function update(User $user, array $params)
    {
        if (isset($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }
        return $this->user_repository->update($user, $params);
    }

    public function updateAvatar(User $user, UploadedFile $file): User
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        return $this->user_repository->updateAvatar($user, $file);
    }


}
