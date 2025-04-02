<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkingHoursForm extends BaseAdminComponent
{
    #[Modelable]
    public array $hours = [];

    public function mount(array $existing = [])
    {
        $this->hours = collect(range(0, 6))->mapWithKeys(fn($day) => [
            'day_' . $day => [
                'active' => false,
                'open_time' => '',
                'close_time' => '',
            ]
        ])->toArray();

        foreach ($existing as $key => $wh) {
            if ($wh['active']) {
                $this->hours[$key] = [
                    'active' => true,
                    'open_time' => $wh['open_time'] ?? '',
                    'close_time' => $wh['close_time'] ?? '',
                ];
            }
        }
    }

    #[On('working-hours.updated')]
    public function updateDay($payload)
    {
        error_log('Updating day: ' . json_encode($payload));
        $this->hours['day_' . $payload['day']] = $payload['data'];
        error_log('Hours: ' . json_encode($this->hours));
    }

    public function render()
    {
        return view('livewire.admin.working-hours-form');
    }
}
