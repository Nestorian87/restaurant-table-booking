<?php

namespace App\Livewire\Admin\Restaurants\Partials;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\WithFileUploads;

class GalleryUploadForm extends BaseAdminComponent
{
    use WithFileUploads;

    public int $restaurantId;
    public $photo;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function save()
    {
        $this->validate([
            'photo' => 'required|image|max:10000',
        ]);

        $this->handleApiResult(
            $this->repository->uploadPhoto($this->restaurantId, $this->photo),
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );

        if ($this->photo) {
            $this->photo->delete();
        }

        $this->reset('photo');

        $this->dispatch('photoUploaded');
    }

    public function render()
    {
        return view('livewire.admin.restaurants.partials.gallery-upload-form');
    }
}

