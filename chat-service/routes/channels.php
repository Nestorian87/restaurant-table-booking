<?php

use App\Broadcasting\AdminChatsChannel;
use App\Broadcasting\ChatChannel;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['jwt.signature']]);
Broadcast::channel('chat.{chatId}', ChatChannel::class);
Broadcast::channel('admin.chats', AdminChatsChannel::class);
