<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Illuminate\Http\UploadedFile;
use Livewire\WithFileUploads;

class MenuItemForm extends BaseAdminComponent
{
    use WithFileUploads;

    public int $restaurantId;
    public array $menuCategories = [];

    public ?int $editId = null;
    public string $name = '';
    public string $description = '';
    public int $price = 1;
    public ?int $menu_category_id = null;
    public string $unit = 'g';
    public int $volume = 1;
    public ?UploadedFile $photo = null;
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
            $this->description = $this->editing['description'] ?? '';
            $this->price = $this->editing['price'];
            $this->menu_category_id = $this->editing['menu_category_id'];
            $this->unit = $this->editing['unit'];
            $this->volume = $this->editing['volume'];
        }
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:300',
            'price' => 'required|numeric|min:1',
            'menu_category_id' => 'required|numeric',
            'unit' => 'required|string',
            'volume' => 'required|numeric|min:1',
            'photo' => 'nullable|image|max:10000',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'menu_category_id' => $this->menu_category_id,
            'unit' => $this->unit,
            'volume' => $this->volume,
        ];

        if ($this->editId) {
            $this->handleApiResult(
                $this->repository->updateMenuItem($this->editId, $data, $this->photo),
                onSuccess: function () {
                    $this->reset(['name', 'description', 'price', 'menu_category_id', 'unit', 'volume', 'photo', 'editId']);
                    return $this->dispatch('menu:updated');
                }
            );
        } else {
            $this->handleApiResult(
                $this->repository->createMenuItem($this->restaurantId, $data, $this->photo),
                onSuccess: function () {
                    $this->reset(['name', 'description', 'price', 'menu_category_id', 'unit', 'volume', 'photo', 'editId']);
                    return $this->dispatch('menu:updated');
                }
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.menu-item-form');
    }
}
