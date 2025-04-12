<?php

namespace App\Livewire\Common;

use Livewire\Component;

class MenuCategoryScroller extends Component
{
    public array $menuCategories = [];

    public function render()
    {
        return view('livewire.common.menu-category-scroller');
    }
}
