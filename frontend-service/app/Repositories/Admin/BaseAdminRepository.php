<?php

namespace App\Repositories\Admin;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cookie;

abstract class BaseAdminRepository extends BaseRepository
{
    protected function getToken(): ?string
    {
        return Cookie::get('admin_token');
    }
}
