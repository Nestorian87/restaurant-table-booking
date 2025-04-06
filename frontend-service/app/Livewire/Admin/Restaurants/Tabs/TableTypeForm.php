<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class TableTypeForm extends BaseAdminComponent
{
    public int $restaurantId;
    public string $places = '0';
    public int $count = 0;
    public ?int $editId = null;
    public array $tableTypes = [];

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $this->fetchTableTypes();
    }

    public function fetchTableTypes()
    {
        $this->handleApiResult(
            $this->repository->find($this->restaurantId),
            onSuccess: fn($data) => $this->tableTypes = $data['table_types'] ?? []
        );
        $this->dispatch('table-type:refresh', $this->tableTypes);
    }

    #[On('table-edit-requested')]
    public function fillForm($table)
    {
        $this->editId = $table['id'];
        $this->places = (string) $table['places_count'];
        $this->count = $table['tables_count'];
        $this->dispatch('table-editing-changed', $this->editId);
    }

    #[On('table-delete-requested')]
    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $id,
            'key' => 'table-type',
            'title' => __('admin.table_type_confirm_delete'),
        ]);
    }

    #[On('table-type:delete-confirmed')]
    public function deleteConfirmed(int $id): void
    {
        $this->handleApiResult(
            $this->repository->deleteTableType($id),
            onSuccess: fn() => $this->fetchTableTypes()
        );
    }

    public function checkExistingPlaces($value)
    {
        $value = (int) $value;

        $existing = collect($this->tableTypes)->firstWhere('places_count', $value);

        if ($existing) {
            $this->editId = $existing['id'];
            $this->places = $value;
        } else {
            $this->reset('editId');
        }

        $this->dispatch('table-editing-changed', $this->editId);
    }

    public function updatedPlaces($value)
    {
        $this->checkExistingPlaces($value);
    }

    public function submit()
    {
        $this->validate([
            'places' => 'required|integer|min:1',
            'count' => 'required|integer|min:1',
        ]);

        $data = [
            'places_count' => (int) $this->places,
            'tables_count' => $this->count,
        ];

        if ($this->editId) {
            $this->handleApiResult(
                $this->repository->updateTableType($this->editId, $data),
                onSuccess: fn() => $this->fetchTableTypes()
            );
        } else {
            $this->handleApiResult(
                $this->repository->createTableType($this->restaurantId, $data),
                onSuccess: fn() => $this->fetchTableTypes()
            );
        }

        $this->reset(['editId', 'places', 'count']);
        $this->fetchTableTypes();
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.table-type-form', [
            'tableTypes' => $this->tableTypes,
        ]);
    }
}
