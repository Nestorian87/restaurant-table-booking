<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class MenuCategoryForm extends BaseAdminComponent
{
    public int $restaurantId;
    public string $name = '';
    public ?int $editId = null;
    public ?array $editing = null;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        if ($this->editing) {
            $this->editId = $this->editing['id'];
            $this->name = $this->editing['name'];
        }
    }

    public function submit()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $result = $this->editId
            ? $this->repository->updateMenuCategory($this->editId, ['name' => $this->name])
            : $this->repository->createMenuCategory($this->restaurantId, ['name' => $this->name]);

        $this->handleApiResult($result, onSuccess: fn() => $this->dispatch('menu:updated'));

        $this->reset(['name', 'editId']);
    }

    #[On('menu-category:edit')]
    public function fillForm($data)
    {
        $this->editId = $data['id'];
        $this->name = $data['name'];
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.menu-category-form');
    }
}

