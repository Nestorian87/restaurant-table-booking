<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class RestaurantCard extends BaseAdminComponent
{
    public string $class = '';
    public array $restaurant;

    public function render()
    {
        return view('livewire.admin.restaurant-card');
    }
}


