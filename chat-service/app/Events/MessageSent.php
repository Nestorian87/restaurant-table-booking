<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(
        public int $chatId,
        public array $message
    ) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
