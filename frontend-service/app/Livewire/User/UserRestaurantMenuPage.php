<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\RestaurantUserRepository;

class UserRestaurantMenuPage extends BaseUserComponent
{
    public array $menuItems = [];
    public array $menuCategories = [];
    public string $restaurantName = '';

    public int $restaurantId;

    private RestaurantUserRepository $repository;

    public function boot(RestaurantUserRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function mount(int $restaurantId)
    {
        $this->restaurantId = $restaurantId;
        $result = $this->repository->getRestaurantMenu($restaurantId);

        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->restaurantName = $data['restaurant_name'];
            $this->menuItems = $data['items'];
            $this->menuCategories = $data['categories'];
        }, onFailure: fn($response) => $this->dispatch('swal:show', [
            'type' => 'error',
            'title' => __('common.error'),
            'text' => __('common.something_went_wrong'),
        ]));
    }

    public function goBack()
    {
        $this->dispatch('spa:navigate', [
            'url' => route('user.restaurant', ['restaurantId' => $this->restaurantId])
        ]);
    }

    public function render()
    {
        return view('livewire.user.restaurant-menu-page');
    }
}
