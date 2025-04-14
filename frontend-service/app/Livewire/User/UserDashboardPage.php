<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use Illuminate\Support\Facades\Cookie;

class UserDashboardPage extends BaseUserComponent
{
    public function render()
    {
        return view('livewire.user.dashboard-page');
    }
}

