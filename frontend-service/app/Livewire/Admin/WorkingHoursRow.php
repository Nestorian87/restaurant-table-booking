<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class WorkingHoursRow extends Component
{
    public int $day;
    public string $weekdayName;
    public array $state = [
        'active' => false,
        'open_time' => '',
        'close_time' => '',
    ];

    public function mount(int $day, string $weekdayName, array $values = [])
    {

        $this->day = $day;
        $this->weekdayName = $weekdayName;
        $this->state = array_merge($this->state, $values);
    }

    public function updated($property)
    {
        if ($property === 'state.active') {
            if ($this->state['active']) {
                $this->state['open_time'] = '11:00';
                $this->state['close_time'] = '22:00';
            } else {
                $this->state['open_time'] = '';
                $this->state['close_time'] = '';
            }
        }
        $this->dispatch('working-hours.updated', [
            'day' => $this->day,
            'data' => $this->state,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.working-hours-row');
    }
}
