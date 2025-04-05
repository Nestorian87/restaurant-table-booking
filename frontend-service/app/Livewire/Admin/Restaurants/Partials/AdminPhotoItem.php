<?php

namespace App\Livewire\Admin\Restaurants\Partials;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class AdminPhotoItem extends BaseAdminComponent
{
    public array $photo;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete()
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $this->photo['id'],
            'key' => 'restaurant-photo',
            'title' => __('admin.restaurant_photo_confirm_delete'),
            'name' => '',
        ]);
    }

    #[On('restaurant-photo:delete-confirmed')]
    public function deleteConfirmed(int $id): void
    {
        if ($id !== $this->photo['id']) return;
        $this->handleApiResult(
            $this->repository->deletePhoto($this->photo['id']),
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );

        $this->dispatch('photoUploaded');
    }

    public function render()
    {
        return view('livewire.admin.restaurants.partials.admin-photo-item');
    }
}
