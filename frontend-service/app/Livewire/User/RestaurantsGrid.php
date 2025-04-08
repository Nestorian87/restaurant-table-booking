<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\RestaurantUserRepository;
use Livewire\Attributes\On;

class RestaurantsGrid extends BaseUserComponent
{
    public array $restaurants = [];
    public int $page = 1;
    public bool $hasMorePages = true;

    protected RestaurantUserRepository $repository;

    public function boot(RestaurantUserRepository $repository)
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

    public function render()
    {
        return view('livewire.user.restaurants-grid');
    }
}
