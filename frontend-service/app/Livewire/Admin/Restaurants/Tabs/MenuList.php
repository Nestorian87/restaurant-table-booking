<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use Livewire\Attributes\On;

class MenuList extends BaseAdminComponent
{
    public array $menuItems = [];
    public array $menuCategories = [];

    #[On('menu:updated')]
    public function updateMenuItems($items)
    {
        $this->menuItems = $items;
    }

    public function editItem($item)
    {
        $this->dispatch('menu-item:edit', $item);
    }

    public function deleteItem($id)
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $id,
            'key' => 'menu-item',
            'title' => __('admin.menu_item_confirm_delete')
        ]);
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.menu-list');
    }
}
