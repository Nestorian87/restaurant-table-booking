<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

#[On('photoUploaded')]
class GalleryTab extends BaseAdminComponent
{
    use WithFileUploads;

    public int $restaurantId;
    public array $photos;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete($photoId)
    {
        $this->handleApiResult(
            $this->repository->deletePhoto($photoId),
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );
    }

    public function render()
    {
        $this->handleApiResult(
            $this->repository->find($this->restaurantId),
            onSuccess: fn($data) => $this->photos = $data['photos'] ?? [],
            onFailure: fn() => session()->flash('error', __('common.something_went_wrong'))
        );

        return view('livewire.admin.restaurants.tabs.gallery-tab', [
            'restaurantId' => $this->restaurantId
        ]);
    }
}

