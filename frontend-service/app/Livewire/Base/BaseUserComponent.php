<?php

namespace App\Livewire\Base;

class BaseUserComponent extends BaseComponent
{
    protected function getTokenCookieName(): string
    {
        return 'user_token';
    }

    protected function getLoginRoute(): string
    {
        return 'login';
    }
}
