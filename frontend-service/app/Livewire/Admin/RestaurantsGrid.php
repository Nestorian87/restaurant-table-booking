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
    public int $page = 1;
    public bool $hasMorePages = true;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $this->loadMore();
    }


    public function loadMore(): void
    {
        $response = $this->repository->all($this->page);

        $this->handleApiResult($response,
            onSuccess: function ($data) {
                $this->restaurants = array_merge($this->restaurants, $data['data'] ?? []);
                $this->hasMorePages = $data['meta']['current_page'] < $data['meta']['last_page'];
                $this->page++;
            },
            onFailure: function () {
                $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => __('common.something_went_wrong'),
                ]);
            }
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

