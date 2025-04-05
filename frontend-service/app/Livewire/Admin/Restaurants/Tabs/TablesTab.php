<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class TablesTab extends BaseAdminComponent
{
    public int $restaurantId;
    public string $places = '0';
    public int $count = 0;
    public array $tableTypes;
    public ?int $editId = null;

    protected RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function add()
    {
        $this->validate([
            'places' => 'required|integer|min:1',
            'count' => 'required|integer|min:1',
        ]);

        $this->handleApiResult(
            $this->repository->createTableType($this->restaurantId, [
                'places_count' => (int) $this->places,
                'tables_count' => $this->count,
            ]),
            onSuccess: fn() => $this->dispatch('table-type:updated')
        );

        $this->reset(['places', 'count']);
    }

    public function edit($id)
    {
        $type = collect($this->tableTypes)->firstWhere('id', $id);
        if ($type) {
            $this->editId = $id;
            $this->places = (string) $type['places_count'];
            $this->count = $type['tables_count'];
        }
    }

    public function update()
    {
        if (!$this->editId) return;

        $this->validate([
            'places' => 'required|integer|min:1',
            'count' => 'required|integer|min:1',
        ]);

        $this->handleApiResult(
            $this->repository->updateTableType($this->editId, [
                'places_count' => (int) $this->places,
                'tables_count' => $this->count,
            ]),
            onSuccess: fn() => $this->fetchTableTypes(),
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );

        $this->reset(['editId', 'places', 'count']);
    }

    public function delete($id)
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $id,
            'key' => 'table-type',
            'title' => __('admin.table_type_confirm_delete'),
            'name' => '',
        ]);
    }

    #[On('table-type:delete-confirmed')]
    public function deleteConfirmed(int $id): void
    {
        $this->handleApiResult(
            $this->repository->deleteTableType($id),
            onSuccess: fn() => $this->dispatch('table-type:updated')
        );
    }

    public function fetchTableTypes()
    {
        $this->handleApiResult(
            $this->repository->find($this->restaurantId),
            onSuccess: function ($data) {
                $this->tableTypes = $data['table_types'] ?? [];
            },
            onFailure: fn() => session()->flash('error', __('common.something_went_wrong'))
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
    }

    public function updatedPlaces($value)
    {
        $this->checkExistingPlaces($value);
    }

    public function render()
    {
        $this->fetchTableTypes();
        return view('livewire.admin.restaurants.tabs.tables-tab', [
        ]);
    }
}

