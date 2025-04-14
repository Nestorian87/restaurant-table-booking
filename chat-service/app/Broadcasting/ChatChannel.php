<?php

namespace App\Broadcasting;

use Tymon\JWTAuth\Facades\JWTAuth;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join($user, $chatId): array|bool
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            $userId = $payload->get('sub');
            if ($userId == intval($chatId) || $payload->get('role') === 'admin') {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
