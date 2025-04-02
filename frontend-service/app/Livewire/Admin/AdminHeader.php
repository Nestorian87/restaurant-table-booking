<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class AdminHeader extends BaseAdminComponent
{
    public function logout()
    {
        Cookie::queue(Cookie::forget('admin_token'));
        $this->dispatch('spa:navigate', [
            'url' => route('admin.login')
        ]);
    }

    public function render()
    {
        return view('livewire.admin.admin-header');
    }
}
