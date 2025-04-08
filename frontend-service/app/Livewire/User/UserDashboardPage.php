<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use Illuminate\Support\Facades\Cookie;

class UserDashboardPage extends BaseUserComponent
{
    public function logout()
    {
        Cookie::queue(Cookie::forget('user_token'));
        $this->dispatch('spa:navigate', [
            'url' => route('user.login')
        ]);
    }

    public function render()
    {
        return view('livewire.user.dashboard-page');
    }
}

