<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class UserReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'rating_kitchen' => 'required|integer|min:1|max:5',
            'rating_interior' => 'required|integer|min:1|max:5',
            'rating_service' => 'required|integer|min:1|max:5',
            'rating_atmosphere' => 'required|integer|min:1|max:5',
            'text' => 'nullable|string|max:1000',
            'timezone' => 'required|string',
        ]);

        if ($booking->user_id != $request->attributes->get('user_id')) {
            abort(403, 'Unauthorized');
        }

        if ($booking->end_time > Carbon::now($data['timezone'])) {
            throw ValidationException::withMessages(['booking' => 'You can review only completed bookings.']);
        }

        if ($booking->status !== 'confirmed') {
            throw ValidationException::withMessages(['booking' => 'Only confirmed bookings can be reviewed.']);
        }

        if ($booking->review) {
            return response()->json(['message' => 'Review already exists'], 409);
        }

        $review = $booking->review()->create($data);

        return response()->json($review, 201);
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->user_id != $request->attributes->get('user_id')) {
            abort(403, 'Unauthorized');
        }

        $review = $booking->review;

        if (!$review) {
            throw ValidationException::withMessages([
                'review' => 'Review does not exist for this booking.'
            ]);
        }

        $data = $request->validate([
            'rating_kitchen' => 'sometimes|integer|min:1|max:5',
            'rating_interior' => 'sometimes|integer|min:1|max:5',
            'rating_service' => 'sometimes|integer|min:1|max:5',
            'rating_atmosphere' => 'sometimes|integer|min:1|max:5',
            'text' => 'nullable|string|max:1000',
        ]);

        $review->update($data);

        return response()->json($review);
    }
}
