<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cookie;

abstract class BaseUserRepository extends BaseRepository
{
    protected function getToken(): ?string
    {
        return Cookie::get('user_token');
    }
}
