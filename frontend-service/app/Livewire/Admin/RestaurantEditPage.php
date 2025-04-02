<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use Livewire\Component;

class RestaurantEditPage extends BaseAdminComponent
{
    public ?int $restaurantId = null;

    public function mount(int $restaurantId)
    {
        $this->restaurantId = $restaurantId;
    }

    public function render()
    {
        return view('livewire.admin.restaurant-edit-page', [
            'restaurantId' => $this->restaurantId
        ]);
    }
}
