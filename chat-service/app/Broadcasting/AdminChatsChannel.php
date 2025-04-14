<?php

namespace App\Broadcasting;

use Tymon\JWTAuth\Facades\JWTAuth;

class AdminChatsChannel
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
    public function join($user): array|bool
    {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
            if ($payload->get('role') === 'admin') {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
