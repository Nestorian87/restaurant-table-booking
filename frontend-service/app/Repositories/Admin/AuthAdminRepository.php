<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;

class AuthAdminRepository extends BaseAdminRepository
{
    public function login(string $email, string $password): ApiResult
    {
        return $this->request("/users/admin/login", "POST", [
            'email' => $email,
            'password' => $password,
        ]);
    }
}
