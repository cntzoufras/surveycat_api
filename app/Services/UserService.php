<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService {

    protected UserRepository $user_repository;

    public function __construct(UserRepository $user_repository) {
        $this->user_repository = $user_repository;
    }

    public function show($params) {
        return $this->user_repository->getIfExist($params);
    }

    public function update($user, array $params) {
        $user = $this->user_repository->resolveModel($user);
        return $this->user_repository->update($user, $params);
    }

}