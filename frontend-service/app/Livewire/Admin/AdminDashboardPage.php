<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AdminDashboardPage extends BaseAdminComponent
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
        return view('livewire.admin.dashboard-page');
    }
}
