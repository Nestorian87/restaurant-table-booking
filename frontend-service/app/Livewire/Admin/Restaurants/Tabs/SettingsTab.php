<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use Livewire\Component;
use App\Models\Restaurant;

class SettingsTab extends Component
{
    public ?int $restaurantId = null;

    public function mount(int $restaurantId)
    {
        $this->restaurantId = $restaurantId;
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.settings-tab');
    }
}
