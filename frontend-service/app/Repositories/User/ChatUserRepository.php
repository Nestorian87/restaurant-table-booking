<?php

namespace App\Repositories\User;

use App\Dto\ApiResult;
use App\Repositories\Admin\BaseAdminRepository;

class ChatUserRepository extends BaseUserRepository
{

    public function getChat(): ApiResult
    {
        return $this->request("/chat/");
    }

    public function getMessages($page = 1, $perPage = 20): ApiResult
    {
        return $this->request("/chat/messages?page=$page&per_page=$perPage");
    }

    public function sendMessage($content): ApiResult
    {
        return $this->request("/chat/messages", 'POST', [
            'content' => $content
        ]);
    }

    public function markAsRead()
    {
        return $this->request("/chat/messages/read", 'POST');
    }
}
