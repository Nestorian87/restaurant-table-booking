<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;

class RestaurantAdminRepository extends BaseAdminRepository
{
    public function find(int $id): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$id}");
    }

    public function all(int $page = 1): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants?page=$page&per_page=15");
    }


    public function create(array $data): ApiResult
    {
        return $this->request('/restaurants/admin/restaurants', 'POST', $data);
    }

    public function update(int $id, array $data): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$id}", 'PUT', $data);
    }

    public function delete(int $id): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$id}", 'DELETE');
    }
}
