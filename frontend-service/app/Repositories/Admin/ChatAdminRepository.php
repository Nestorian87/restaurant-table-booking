<?php

namespace App\Repositories\Admin;

use App\Dto\ApiResult;

class ChatAdminRepository extends BaseAdminRepository
{
    public function getChats(int $page = 1, int $perPage = 20): ApiResult
    {
        return $this->request("/chat/admin/chats?page=$page&per_page=$perPage");
    }

    public function getUnreadCount(): ApiResult
    {
        return $this->request("/chat/admin/chats/unread");
    }

    public function getChat(int $userId): ApiResult
    {
        return $this->request("/chat/admin/chats/$userId");
    }

    public function getMessages($chatId, $page = 1, $perPage = 20): ApiResult
    {
        return $this->request("/chat/admin/messages/$chatId?page=$page&per_page=$perPage");
    }

    public function sendMessage($chatId, $content): ApiResult
    {
        return $this->request("/chat/admin/messages/$chatId", 'POST', [
            'content' => $content
        ]);
    }

    public function markAsRead($chatId): ApiResult
    {
        return $this->request("/chat/admin/messages/$chatId/read", 'POST');
    }
}
