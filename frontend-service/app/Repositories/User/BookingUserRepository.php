<?php

namespace App\Repositories\User;

use App\Dto\ApiResult;
use App\Repositories\Admin\BaseAdminRepository;
use Illuminate\Http\UploadedFile;
use function Symfony\Component\Translation\t;

class BookingUserRepository extends BaseUserRepository
{
    public function createBooking(array $data): ApiResult
    {
        return $this->request('/bookings/bookings', 'POST', $data);
    }

    public function getRestaurantAvailableTables(int $restaurantId, string $startTime, string $endTime): ApiResult
    {
        return $this->request("/bookings/available-tables/restaurants/$restaurantId?start_time=$startTime&end_time=$endTime");
    }

    public function getActiveRestaurantBooking(int $restaurantId): ApiResult
    {
        return $this->request("/bookings/bookings/restaurants/$restaurantId/active");
    }

    public function getBookings(): ApiResult
    {
        return $this->request('/bookings/bookings');
    }

    public function cancelBooking(int $bookingId): ApiResult
    {
        return $this->request("/bookings/bookings/{$bookingId}/cancel", 'POST');
    }

    public function leaveReview(int $bookingId, array $data): ApiResult
    {
        return $this->request("/bookings/bookings/{$bookingId}/review", 'POST', $data);
    }

}
