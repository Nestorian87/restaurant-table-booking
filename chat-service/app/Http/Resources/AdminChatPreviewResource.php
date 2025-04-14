<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminChatPreviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => [
                'id' => $this['user']->id,
                'name' => $this['user']->name,
                'surname' => $this['user']->surname,
            ],
            'last_message' => $this['last_message']
                ? [
                    'id' => $this['last_message']->id,
                    'content' => $this['last_message']->content,
                    'created_at' => $this['last_message']->created_at,
                ]
                : null,
            'messages_count' => $this['messages_count'],
            'unread_count' => $this['unread_count'],
        ];
    }
}

