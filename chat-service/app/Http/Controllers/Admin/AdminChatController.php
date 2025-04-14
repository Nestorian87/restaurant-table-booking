<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminChatPreviewResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 20);

        $users = User::whereIn('id', function ($query) {
            $query->select('user_id')->from('messages');
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $users->getCollection()->transform(function ($user) {
            $lastMessage = Message::where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->first();

            $messagesCount =Message::where('user_id', $user->id)->count();
            $unreadCount = Message::where('user_id', $user->id)
                ->where('from_user', true)
                ->where('is_viewed', false)
                ->count();

            return [
                'user' => $user,
                'last_message' => $lastMessage,
                'messages_count' => $messagesCount,
                'unread_count' => $unreadCount
            ];
        });

        return AdminChatPreviewResource::collection($users)->response();
    }

    public function unread(Request $request): JsonResponse
    {
        $unreadMessagesCount = Message::where('from_user', true)
            ->where('is_viewed', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadMessagesCount
        ]);
    }

    public function show(Request $request, int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $lastMessage = Message::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        $messagesCount = Message::where('user_id', $userId)->count();
        $unreadCount = Message::where('user_id', $userId)
            ->where('from_user', true)
            ->where('is_viewed', false)
            ->count();

        $chatPreview = [
            'user' => $user,
            'last_message' => $lastMessage,
            'messages_count' => $messagesCount,
            'unread_count' => $unreadCount
        ];

        return AdminChatPreviewResource::make($chatPreview)->response();
    }

}
