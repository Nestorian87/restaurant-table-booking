<?php

namespace App\Repositories\User;

use App\Dto\ApiResult;
use App\Repositories\Admin\BaseAdminRepository;
use Illuminate\Http\UploadedFile;
use function Symfony\Component\Translation\t;

class ProfileUserRepository extends BaseUserRepository
{
    public function getProfile(): ApiResult
    {
        return $this->request("/users/profile");
    }

    public function updateProfile(string $name, string $surname, string $email, ?string $password): ApiResult
    {
        $data = [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
        ];

        if ($password) {
            $data['password'] = $password;
        }

        return $this->request("/users/profile", "PUT", $data);
    }
}
