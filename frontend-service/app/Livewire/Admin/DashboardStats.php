<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use Livewire\Component;

class DashboardStats extends BaseAdminComponent
{
    public int $users = 152;
    public int $reservations = 37;
    public int $reviews = 5;

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
