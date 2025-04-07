<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminRestaurantBookingController extends Controller
{
    public function index(Request $request, int $restaurantId)
    {
        $query = Booking::with(['user', 'tableTypes', 'review'])
            ->where('restaurant_id', $restaurantId);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_time')) {
            $query->where('start_time', '>=', $request->start_time);
        }

        if ($request->filled('end_time')) {
            $query->where('end_time', '<=', $request->end_time);
        }

        $sortBy = $request->get('sort_by', 'start_time');
        $sortDir = $request->get('sort_dir', 'desc');

        if (in_array($sortBy, ['start_time', 'end_time', 'status', 'created_at'])) {
            $query->orderBy($sortBy, $sortDir);
        }

        $perPage = $request->get('per_page', 10);
        $bookings = $query->paginate($perPage);

        return BookingResource::collection($bookings);
    }

    public function cancel(int $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'Booking is already cancelled.'], 400);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return response()->json(['message' => 'Booking cancelled successfully.']);
    }
}
