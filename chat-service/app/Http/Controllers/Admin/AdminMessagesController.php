<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Events\NewMessageForAdmin;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminMessagesController extends Controller
{
    public function index(Request $request, int $userId)
    {
        Message::where('user_id', $userId)
            ->where('from_user', true)
            ->where('is_viewed', false)
            ->update(['is_viewed' => true]);

        $messages = Message::where('user_id', $userId)
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return MessageResource::collection($messages);
    }

    public function store(Request $request, int $userId)
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'user_id'   => $userId,
            'content'   => $data['content'],
            'from_user' => false,
        ]);

        $messageData = [
            'id' => $message->id,
            'content' => $message->content,
            'user_id' => $message->user_id,
            'from_user' => $message->from_user,
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name,
                'surname' => $message->user->surname,
            ],
            'created_at' => $message->created_at->toISOString(),
        ];

        MessageSent::dispatch($message->user_id, $messageData);
        NewMessageForAdmin::dispatch($messageData);

        return new MessageResource($message);
    }

    public function markAsRead(Request $request, int $userId)
    {
        Message::where('user_id', $userId)
            ->where('from_user', true)
            ->where('is_viewed', false)
            ->update(['is_viewed' => true]);

        return response()->json(['message' => 'Messages marked as read']);
    }
}
