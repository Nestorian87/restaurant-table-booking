<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;
use Illuminate\Http\UploadedFile;
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

    public function getMenuCategories(int $restaurantId): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$restaurantId}/menu-categories");
    }

    public function createMenuCategory(int $restaurantId, array $data): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$restaurantId}/menu-categories", 'POST', $data);
    }

    public function updateMenuCategory(int $categoryId, array $data): ApiResult
    {
        return $this->request("/restaurants/admin/menu-categories/{$categoryId}", 'PUT', $data);
    }

    public function deleteMenuCategory(int $categoryId): ApiResult
    {
        return $this->request("/restaurants/admin/menu-categories/{$categoryId}", 'DELETE');
    }

    public function getMenuItems(int $restaurantId): ApiResult
    {
        return $this->request("/restaurants/admin/restaurants/{$restaurantId}/menu-items");
    }

    public function createMenuItem(int $restaurantId, array $data, ?UploadedFile $photo = null): ApiResult
    {
        $multipart = [];

        foreach ($data as $key => $val) {
            $multipart[] = ['name' => $key, 'contents' => (string)$val];
        }

        if ($photo) {
            $multipart[] = [
                'name' => 'photo',
                'contents' => file_get_contents($photo->getRealPath()),
                'filename' => $photo->getClientOriginalName(),
            ];
        }

        return $this->request("/restaurants/admin/restaurants/{$restaurantId}/menu-items", 'POST', $multipart, isMultipart: true);
    }

    public function updateMenuItem(int $itemId, array $data, ?UploadedFile $photo = null): ApiResult
    {
        $multipart = [['name' => '_method', 'contents' => 'PUT']];

        foreach ($data as $key => $val) {
            $multipart[] = ['name' => $key, 'contents' => (string)$val];
        }

        if ($photo) {
            $multipart[] = [
                'name' => 'photo',
                'contents' => file_get_contents($photo->getRealPath()),
                'filename' => $photo->getClientOriginalName(),
            ];
        }

        return $this->request("/restaurants/admin/menu-items/{$itemId}", 'POST', $multipart, isMultipart: true);
    }

    public function deleteMenuItem(int $itemId): ApiResult
    {
        return $this->request("/restaurants/admin/menu-items/{$itemId}", 'DELETE');
    }

    public function getRestaurantBookings(
        int $restaurantId,
        ?string $status = null,
        string $sortBy = 'start_time',
        string $sortDir = 'desc',
        int $page = 1,
        int $perPage = 10
    ): ApiResult {
        $query = http_build_query(array_filter([
            'status' => $status,
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
            'page' => $page,
            'per_page' => $perPage,
        ]));

        error_log('Making request to get restaurant bookings with query: ' . $query);

        return $this->request("/bookings/admin/bookings/restaurants/{$restaurantId}?{$query}");
    }

    public function cancelBooking(int $bookingId)
    {
        return $this->request("/bookings/admin/bookings/{$bookingId}/cancel", 'POST');
    }
}
