<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use Livewire\Component;

class RestaurantsGrid  extends BaseAdminComponent
{
    public array $restaurants = [];
    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $this->loadRestaurants();
    }

    public function loadRestaurants(): void
    {
        $this->handleApiResult(
            $this->repository->all(),
            onSuccess: fn($data) => $this->restaurants = $data,
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );
    }

    #[On('restaurant:deleted')]
    public function removeRestaurant($id): void
    {
        $this->loadRestaurants();
    }

    public function render()
    {
        return view('livewire.admin.restaurants-grid');
    }
}

