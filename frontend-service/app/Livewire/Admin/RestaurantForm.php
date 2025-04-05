<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class RestaurantForm extends BaseAdminComponent
{
    public ?int $restaurantId = null;

    public string $name = '';
    public string $location = '';
    public string $phone = '';
    public string $description = '';
    public int $maxBookingPlaces = 0;
    public array $workingHours = [];

    protected RestaurantAdminRepository $repository;

    private string $restaurantName = '';

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount(?int $restaurantId = null)
    {
        $this->restaurantId = $restaurantId;

        if ($restaurantId) {
            $this->handleApiResult(
                $this->repository->find($restaurantId),
                onSuccess: function ($data) {
                    $this->restaurantName = $data['name'] ?? '';
                    $this->fill([
                        'name' => $this->restaurantName,
                        'location' => $data['location'],
                        'phone' => $data['phone'] ?? '',
                        'description' => $data['description'] ?? '',
                        'maxBookingPlaces' => $data['max_booking_places'] ?? 0,
                    ]);

                    $workingHours = [];

                    foreach (range(0, 6) as $day) {
                        $existing = collect($data['working_hours'] ?? [])
                            ->firstWhere('day', $day);

                        $workingHours['day_' . $day] = [
                            'active' => !is_null($existing),
                            'open_time' => $existing['open_time'] ?? '',
                            'close_time' => $existing['close_time'] ?? '',
                        ];
                    }

                    $this->workingHours = $workingHours;
                },
                onFailure: function () {
                    $this->dispatch('swal:show', [
                        'type' => 'error',
                        'title' => __('common.error'),
                        'text' => __('common.something_went_wrong'),
                    ]);
                }
            );
        }
    }

    public function save()
    {

        $payload = [
            'name' => $this->name,
            'location' => $this->location,
            'phone' => $this->phone,
            'description' => $this->description,
            'max_booking_places' => $this->maxBookingPlaces,
            'working_hours' => collect($this->workingHours)
                ->filter(fn($wh) => is_array($wh) && ($wh['active'] ?? false))
                ->map(fn($wh, $day) => [
                    'day' => (int)str_replace('day_', '', $day),
                    'open_time' => $wh['open_time'],
                    'close_time' => $wh['close_time'],
                ])->values(),
        ];

        if (!$this->restaurantId) {
            $this->handleApiResult(
                $this->repository->create($payload),
                onSuccess: function ($data) {
                    $this->dispatch('spa:navigate', [
                            'url' => route('admin.restaurants.edit', ['restaurantId' => $data['id']])
                        ]
                    );
                },
                onFailure: fn() => $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => __('common.something_went_wrong'),
                ])
            );
            return;
        }

        $this->handleApiResult(
            $this->repository->update($this->restaurantId, $payload),
            onSuccess: function () {
                $this->dispatch('swal:show', [
                    'type' => 'success',
                    'title' => __('admin.restaurant_updated'),
                    'timer' => 2000,
                ]);
            },
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );
    }

    public function delete(): void
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $this->restaurantId,
            'title' => __('admin.restaurant_confirm_delete'),
            'name' => $this->restaurantName,
            'key' => 'restaurant',
        ]);
    }

    #[On('restaurant:delete-confirmed')]
    public function deleteConfirmed(int $id): void
    {
        if ($id !== $this->restaurantId) return;

        $result = $this->repository->delete($id);
        $this->handleApiResult(
            $result,
            onSuccess: function () use ($id) {
                $this->dispatch('restaurant:deleted', id: $id);
                $this->dispatch('spa:navigate', ['url' => route('admin.dashboard')]);
            },
            onFailure: function ($result) {
                $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => $result->message ?? __('common.something_went_wrong'),
                ]);
            }
        );
    }

    public function render()
    {
        return view('livewire.admin.restaurant-form');
    }
}
