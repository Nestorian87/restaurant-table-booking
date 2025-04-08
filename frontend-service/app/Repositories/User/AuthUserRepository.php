<?php

namespace App\Repositories\User;

use App\Dto\ApiResult;
use App\Repositories\Admin\BaseAdminRepository;

class AuthUserRepository extends BaseAdminRepository
{

    public function register(string $name, string $surname, string $email, string $password): ApiResult
    {
        return $this->request('/users/register', 'POST', [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function login(string $email, string $password): ApiResult
    {
        return $this->request("/users/login", "POST", [
            'email' => $email,
            'password' => $password,
        ]);
    }
}
