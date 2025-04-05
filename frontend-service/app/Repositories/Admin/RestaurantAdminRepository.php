<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;
use function Symfony\Component\Translation\t;

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

    public function uploadPhoto(int $restaurantId, mixed $photo): ApiResult
    {
        error_log("Photo " . file_get_contents($photo->getRealPath()));
        return $this->request(
            "/restaurants/admin/restaurants/{$restaurantId}/photos",
            'POST',
            [
                [
                    'name' => 'photos[]',
                    'contents' => file_get_contents($photo->getRealPath()),
                    'filename' => $photo->getClientOriginalName(),
                ]
            ],
            isMultipart: true
        );
    }

    public function deletePhoto(int $photoId): ApiResult
    {
        return $this->request(
            "/restaurants/admin/restaurant-photos/$photoId",
            'DELETE'
        );
    }

    public function getTableTypes(int $restaurantId): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$restaurantId}");
    }

    public function createTableType(int $restaurantId, array $data): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$restaurantId}/table-types", 'POST', $data);
    }

    public function updateTableType(int $id, array $data): ApiResult
    {
        return $this->request("/restaurants/admin/table-types/{$id}", 'PUT', $data);
    }

    public function deleteTableType(int $tableTypeId): ApiResult
    {
        return $this->request("/restaurants/admin/table-types/{$tableTypeId}", 'DELETE');
    }

}
