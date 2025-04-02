<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class RestaurantCard extends BaseAdminComponent
{
    public string $class = '';
    public array $restaurant;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete(): void
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $this->restaurant['id'],
            'name' => $this->restaurant['name'],
        ]);
    }

    #[On('restaurant:delete-confirmed')]
    public function deleteConfirmed(int $id): void
    {
        if ($id !== $this->restaurant['id']) return;
        $result = $this->repository->delete($id);
        $this->handleApiResult(
            $result,
            onSuccess: function () use ($id) {
                $this->dispatch('restaurant:deleted', id: $id);
            },
            onFailure: function ($result) {
                $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => $result->message ?? __('common.something_went_wrong'),
                ]);
            }
        );
    }

    public function render()
    {
        return view('livewire.admin.restaurant-card');
    }
}


