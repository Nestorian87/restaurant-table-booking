<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;

class RestaurantForm extends BaseAdminComponent
{
    public ?int $restaurantId = null;

    public string $name = '';
    public string $location = '';
    public string $phone = '';
    public string $description = '';
    public array $workingHours = [];

    protected RestaurantAdminRepository $repository;

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
                    $this->fill([
                        'name' => $data['name'],
                        'location' => $data['location'],
                        'phone' => $data['phone'] ?? '',
                        'description' => $data['description'] ?? '',
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
            'working_hours' => collect($this->workingHours)
                ->filter(fn($wh) => is_array($wh) && ($wh['active'] ?? false))
                ->map(fn($wh, $day) => [
                    'day' => (int) str_replace('day_', '', $day),
                    'open_time' => $wh['open_time'],
                    'close_time' => $wh['close_time'],
                ])->values(),
        ];

        $this->handleApiResult(
            $this->repository->update($this->restaurantId, $payload),
            onSuccess: fn() => $this->dispatch('spa:navigate', [
                'url' => route('admin.dashboard')
            ]),
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );
    }

    public function render()
    {
        return view('livewire.admin.restaurant-form');
    }
}
