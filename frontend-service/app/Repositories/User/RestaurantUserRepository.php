<?php

namespace App\Repositories\User;

use App\Dto\ApiResult;
use App\Repositories\Admin\BaseAdminRepository;
use Illuminate\Http\UploadedFile;
use function Symfony\Component\Translation\t;

class RestaurantUserRepository extends BaseUserRepository
{
    public function all(int $page = 1): ApiResult
    {
        return $this->request("/restaurants/restaurants?page=$page&per_page=15");
    }
}
