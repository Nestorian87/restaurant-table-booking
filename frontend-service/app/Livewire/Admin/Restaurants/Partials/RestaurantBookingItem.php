<?php

namespace App\Livewire\Admin\Restaurants\Partials;

use Livewire\Component;

class RestaurantBookingItem extends Component
{
    public array $booking;
    public int $restaurantId;
    public function cancel()
    {
        $this->dispatch('booking:cancel', $this->booking['id']);
    }

    public function render()
    {
        return view('livewire.admin.restaurants.partials.restaurant-booking-item');
    }
}
