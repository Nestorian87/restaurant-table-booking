<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;

class StatisticsAdminRepository extends BaseAdminRepository
{
    public function getUsersStatistics(): ApiResult
    {
        return $this->request("/users/admin/users/count");
    }

    public function getBookingsStatistics(): ApiResult
    {
        return $this->request("/bookings/admin/bookings/statistics");
    }
}
