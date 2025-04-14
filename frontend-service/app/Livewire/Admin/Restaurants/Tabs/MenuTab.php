<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class MenuTab extends BaseAdminComponent
{
    public int $restaurantId;
    public array $menuItems = [];
    public array $menuCategories = [];
    public string $page = 'list';
    public ?int $selectedCategoryId = null;

    public ?array $editingCategory = null;
    public ?array $editingItem = null;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $this->fetchMenuData();
    }

    #[On('menu:updated')]
    public function fetchMenuData($sendEvent = false)
    {
        $this->handleApiResult(
            $this->repository->getMenuItems($this->restaurantId),
            onSuccess: function ($data) use ($sendEvent) {
                $this->menuItems = $data ?? [];
                if ($sendEvent) {
                    $this->dispatch('menu:updated', $this->menuItems);
                }
            }
        );

        $this->handleApiResult(
            $this->repository->getMenuCategories($this->restaurantId),
            onSuccess: fn($data) => $this->menuCategories = $data ?? []
        );

        $this->page = 'list';
        $this->editingCategory = null;
        $this->editingItem = null;
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->dispatch('menu:category-selected', $categoryId);
    }

    #[On('menu-category:edit')]
    public function editCategory(array $data): void
    {
        $this->editingCategory = $data;
        $this->page = 'category-form';
    }

    #[On('menu-category:delete-confirmed')]
    public function deleteCategory(int $id)
    {
        $this->handleApiResult(
            $this->repository->deleteMenuCategory($id),
            onSuccess: fn() => $this->dispatch('spa:reload')
    );

        if ($this->selectedCategoryId === $id) {
            $this->selectedCategoryId = null;
        }
    }

    #[On('menu-item:edit')]
    public function editMenuItem($item)
    {
        $this->editingItem = $item;
        $this->page = 'item-form';
    }

    #[On('menu-item:delete-confirmed')]
    public function deleteMenuItem(int $id)
    {
        $this->handleApiResult(
            $this->repository->deleteMenuItem($id),
            onSuccess: fn() => $this->fetchMenuData(true)
        );
    }

    public function changePage($page)
    {
        $this->page = $page;
        if ($page == 'list') {
            $this->editingItem = null;
            $this->editingCategory = null;
        }
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.menu-tab', [
            'menuItems' => $this->menuItems,
            'menuCategories' => $this->menuCategories,
        ]);
    }
}
