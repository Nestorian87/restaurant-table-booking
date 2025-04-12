<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewUserReaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserReviewReactionController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $userId = $request->attributes->get('user_id');

        $data = $request->validate([
            'reaction' => ['nullable', Rule::in(['like', 'dislike'])],
        ]);

        if (is_null($data['reaction'])) {
            ReviewUserReaction::where('review_id', $review->id)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'message' => 'Reaction removed.',
                ...$this->countReactions($review->id)
            ]);
        }

        $reaction = ReviewUserReaction::updateOrCreate(
            [
                'review_id' => $review->id,
                'user_id' => $userId,
            ],
            [
                'reaction' => $data['reaction'],
            ]
        );

        return response()->json([
            'message' => 'Reaction saved.',
            'reaction' => $reaction,
            ...$this->countReactions($review->id)
        ]);
    }

    private function countReactions(int $reviewId): array
    {
        return [
            'likes' => ReviewUserReaction::where('review_id', $reviewId)->where('reaction', 'like')->count(),
            'dislikes' => ReviewUserReaction::where('review_id', $reviewId)->where('reaction', 'dislike')->count(),
        ];
    }
}
