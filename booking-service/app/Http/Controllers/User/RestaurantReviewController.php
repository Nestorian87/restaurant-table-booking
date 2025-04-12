<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ReviewUserReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class RestaurantReviewController extends Controller
{
    public function show($restaurant, Request $request): JsonResponse
    {
        $userId = $request->attributes->get('user_id');

        $reviews = Booking::with(['review', 'user'])
            ->where('restaurant_id', $restaurant)
            ->whereHas('review')
            ->get()
            ->map(function ($booking) use ($userId) {
                $review = $booking->review;
                if (!$review) return null;

                $review->setRelation('user', $booking->user);

                $review->likes = ReviewUserReaction::where('review_id', $review->id)->where('reaction', 'like')->count();
                $review->dislikes = ReviewUserReaction::where('review_id', $review->id)->where('reaction', 'dislike')->count();

                $userReaction = ReviewUserReaction::where('review_id', $review->id)
                    ->where('user_id', $userId)
                    ->first();

                $review->user_reaction = $userReaction?->reaction;

                return $review;
            })
            ->filter();

        $count = $reviews->count();

        $average = [
            'kitchen' => $count ? round($reviews->avg('rating_kitchen'), 2) : null,
            'interior' => $count ? round($reviews->avg('rating_interior'), 2) : null,
            'service' => $count ? round($reviews->avg('rating_service'), 2) : null,
            'atmosphere' => $count ? round($reviews->avg('rating_atmosphere'), 2) : null,
        ];
        $average['total'] = $count ? round(collect($average)->avg(), 2) : null;

        return response()->json([
            'reviews' => $reviews->values(),
            'average' => $average,
        ]);
    }
}
