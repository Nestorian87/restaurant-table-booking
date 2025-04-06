<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;
use Livewire\Component;

class TableTypeList extends BaseAdminComponent
{
    public array $tableTypes = [];
    public ?int $editId = null;

    public function edit($table)
    {
        $this->dispatch('table-edit-requested', $table);
    }

    public function delete($id)
    {
        $this->dispatch('table-delete-requested', $id);
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.table-type-list');
    }

    #[On('table-editing-changed')]
    public function updateEditId($id)
    {
        $this->editId = $id;
    }

    #[On('table-type:refresh')]
    public function refreshTableTypes($tableTypes)
    {
        $this->tableTypes = $tableTypes;
        $this->reset('editId');
    }
}
