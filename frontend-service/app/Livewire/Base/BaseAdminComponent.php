<?php

namespace App\Livewire\Base;

class BaseAdminComponent extends BaseComponent
{
    protected function getTokenCookieName(): string
    {
        return 'admin_token';
    }

    protected function getLoginRoute(): string
    {
        return 'admin.login';
    }
}
