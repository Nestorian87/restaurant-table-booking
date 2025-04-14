<?php

namespace App\Livewire\Common;

use Livewire\Attributes\On;
use Livewire\Component;

class MenuCategoryScroller extends Component
{
    public array $menuCategories = [];
    public bool $adminMode = false;

    public function editCategory(int $id, string $name): void
    {
        $this->dispatch('menu-category:edit', [
            'id' => $id,
            'name' => $name,
        ]);
    }

    public function confirmDeleteCategory(int $id): void
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $id,
            'key' => 'menu-category',
            'title' => __('admin.category_confirm_delete'),
        ]);
    }

    public function render()
    {
        return view('livewire.common.menu-category-scroller');
    }
}
