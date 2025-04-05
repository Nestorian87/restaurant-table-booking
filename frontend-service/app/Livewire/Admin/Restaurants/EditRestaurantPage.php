<?php

namespace App\Livewire\Admin\Restaurants;

use Livewire\Component;

class EditRestaurantPage extends Component
{
    public int $restaurantId;
    public string $activeTab = 'settings';

    protected $queryString = [
        'activeTab' => ['except' => 'settings'],
    ];

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.restaurants.edit-restaurant-page');
    }
}


