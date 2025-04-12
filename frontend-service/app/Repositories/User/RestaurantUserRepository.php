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

    public function getRestaurantById(int $restaurantId): ApiResult
    {
        return $this->request("/restaurants/restaurants/$restaurantId");
    }

    public function getRestaurantReviews(int $id): ApiResult
    {
        return $this->request("/bookings/reviews/restaurants/$id");
    }

    public function getRestaurantMenu(int $restaurantId): ApiResult
    {
        return $this->request("/restaurants/restaurants/$restaurantId/menu");
    }

    public function reactToReview(int $reviewId, ?string $reaction): ApiResult
    {
        return $this->request("/bookings/reviews/$reviewId/reaction", 'POST', [
            'reaction' => $reaction,
        ]);
    }
}
