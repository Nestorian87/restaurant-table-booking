<?php

namespace App\Livewire\Common;

use App\Livewire\Base\BaseAdminComponent;

class RestaurantCard extends BaseAdminComponent
{
    public string $class = '';
    public array $restaurant;

    public function render()
    {
        return view('livewire.common.restaurant-card');
    }
}


