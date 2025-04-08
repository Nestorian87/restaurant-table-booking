<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminBookingsStatisticsController extends Controller
{
    public function index(): JsonResponse
    {
        $activeBookings = Booking::where('status', 'confirmed')
            ->where('end_time', '>', Carbon::now())
            ->count();

        $reviewsCount = Review::count();

        return response()->json([
            'active_bookings_count' => $activeBookings,
            'reviews_count' => $reviewsCount,
        ]);
    }
}
