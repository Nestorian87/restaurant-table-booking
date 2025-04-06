<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;

class TablesTab extends BaseAdminComponent
{
    public int $restaurantId;

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.tables-tab');
    }
}

