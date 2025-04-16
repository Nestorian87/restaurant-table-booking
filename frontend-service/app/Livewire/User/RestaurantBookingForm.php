<?php

namespace App\Livewire\User;

use App\Enums\BookingErrorCode;
use App\Livewire\Base\BaseUserComponent;
use App\Models\TableType;
use App\Repositories\User\BookingUserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\On;

class RestaurantBookingForm extends BaseUserComponent
{
    public array $restaurant;

    public string $startDate = '';
    public string $startTime = '';
    public string $duration = '';
    public array $tables = [];
    public array $availableDurations = [];
    public array $availableTableTypes = [];

    public ?int $newTableTypeId = null;
    public $newTableCount = 1;

    public string $timezone = 'UTC';

    protected BookingUserRepository $repository;

    public function boot(BookingUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount(): void
    {
        $this->tables = [];
        $this->updateAvailableTableType();
    }

    protected function updateAvailableTableType(): void
    {
        $usedIds = array_column($this->tables, 'type_id');

        $nextAvailable = collect($this->restaurant['table_types'])
            ->reject(fn($type) => in_array($type['id'], $usedIds))
            ->values()
            ->first();

        if (!isset($nextAvailable['id']) || $this->newTableTypeId !== $nextAvailable['id']) {
            $this->newTableTypeId = $nextAvailable['id'] ?? null;
        }
    }

    public function addTableType(): void
    {
        if (str_contains($this->newTableCount, 'e') ||
            str_contains($this->newTableCount, '.') ||
            str_contains($this->newTableCount, ',') ||
            !$this->newTableTypeId || $this->newTableCount < 1) {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('bookings.invalid_table_count')
            ]);
            return;
        }

        if (in_array($this->newTableTypeId, array_column($this->tables, 'type_id'))) return;

        $availableType = collect($this->availableTableTypes)->firstWhere('id', $this->newTableTypeId);

        if (!$availableType || $this->newTableCount > $availableType['available']) {
            $this->showErrorAlert(__('bookings.exceeds_table_limit', [
                'available' => $availableType['available'] ?? 0
            ]));
            return;
        }

        $this->tables[] = [
            'type_id' => $this->newTableTypeId,
            'count' => $this->newTableCount,
        ];

        $this->newTableCount = 1;
        $this->updateAvailableTableType();
    }

    public function removeTableType($index): void
    {
        unset($this->tables[$index]);
        $this->tables = array_values($this->tables);
        $this->updateAvailableTableType();
    }

    public function updatedStartDate()
    {
        $this->updateAvailableDurations();
        $this->updateAvailableTablesCount();
    }

    public function updatedStartTime()
    {
        $this->updateAvailableDurations();
        $this->updateAvailableTablesCount();
    }

    public function updatedDuration()
    {
        $this->updateAvailableTablesCount();
    }

    protected function updateAvailableDurations(): void
    {
        $this->availableDurations = [];

        if (!$this->startDate || !$this->startTime) {
            return;
        }

        try {
            $start = Carbon::parse("{$this->startDate} {$this->startTime}");
        } catch (\Exception) {
            return;
        }

        $weekday = ($start->dayOfWeekIso + 6) % 7;
        $working = collect($this->restaurant['working_hours'])->firstWhere('day', $weekday);

        if (!$working) return;

        $closeTime = Carbon::parse("{$this->startDate} {$working['close_time']}");
        $interval = 30;
        $temp = clone $start;

        while (true) {
            $temp->addMinutes($interval);
            if ($temp->gt($closeTime)) break;

            $minutes = $start->diffInMinutes($temp);
            $label = $this->formatDurationLabel($minutes);
            $this->availableDurations[$minutes] = $label;
        }

        if (!array_key_exists($this->duration, $this->availableDurations)) {
            $this->duration = array_key_first($this->availableDurations) ?: '';
        }
    }

    protected function formatDurationLabel(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        $label = [];

        if ($hours > 0) {
            $label[] = "{$hours} " . __('bookings.hours');
        }

        if ($remaining > 0) {
            $label[] = "{$remaining} " . __('bookings.minutes');
        }

        return implode(' ', $label);
    }

    protected function updateAvailableTablesCount()
    {
        error_log('startDate: ' . $this->startDate . ', startTime: ' . $this->startTime . ', duration: ' . $this->duration);
        if (!$this->startDate || !$this->startTime || !$this->duration) {
            $this->availableTableTypes = [];
            return;
        }
        $result = $this->repository->getRestaurantAvailableTables(
            $this->restaurant['id'],
            Carbon::parse("$this->startDate $this->startTime")->format('Y-m-d\TH:i:s'),
            Carbon::parse("$this->startDate $this->startTime")->addMinutes((int)$this->duration)->format('Y-m-d\TH:i:s')
        );
        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->availableTableTypes = $data;
        }, onFailure: function () {
            $this->showErrorAlert(__('common.something_went_wrong'));
        });
    }

    #[On('user-timezone')]
    public function setUserTimezone($timezone): void
    {
        if (!empty($timezone)) {
            $this->timezone = $timezone;
        }
    }

    private function showErrorAlert(string $text)
    {
        $this->dispatch('swal:show', [
            'type' => 'error',
            'title' => __('common.error'),
            'text' => $text
        ]);
    }

    public function save(): void
    {
        if (empty($this->tables)) {
            $this->showErrorAlert(__('bookings.no_tables_selected'));
            return;
        }

        $this->validate([
            'startDate' => 'required|date',
            'startTime' => 'required',
            'duration' => 'required|integer|min:30',
            'tables' => 'required|array|min:1',
            'tables.*.type_id' => 'required|integer',
            'tables.*.count' => 'required|integer|min:1',
        ]);

        $start = Carbon::parse("{$this->startDate} {$this->startTime}");
        $end = (clone $start)->addMinutes((int)$this->duration);

        if ($start->isPast()) {
            $this->showErrorAlert(__('bookings.past_time_error'));
            return;
        }

        $weekday = ($start->dayOfWeekIso + 6) % 7;
        $working = collect($this->restaurant['working_hours'])->firstWhere('day', $weekday);

        if (!$working) {
            $this->showErrorAlert(__('bookings.closed_day_error'));
            return;
        }

        $startTime = $start->format('H:i:s');
        $endTime = $end->format('H:i:s');

        if ($startTime < $working['open_time'] || $endTime > $working['close_time']) {
            $this->showErrorAlert(__('bookings.out_of_hours_error'));
        }

        $totalPlaces = 0;
        foreach ($this->tables as $t) {
            $type = collect($this->restaurant['table_types'])->firstWhere('id', $t['type_id']);
            if (!$type) continue;

            $totalPlaces += $type['places_count'] * $t['count'];
        }

        if ($this->restaurant['max_booking_places'] && $totalPlaces > $this->restaurant['max_booking_places']) {
            $this->showErrorAlert(__('bookings.exceeds_max_places', [
                'total' => $totalPlaces,
                'max' => $this->restaurant['max_booking_places']
            ]));
            return;
        }

        $payload = [
            'restaurant_id' => $this->restaurant['id'],
            'start_time' => $start->format('Y-m-d\TH:i:s'),
            'end_time' => $end->format('Y-m-d\TH:i:s'),
            'timezone' => $this->timezone,
            'table_types' => array_map(fn($t) => [
                'id' => $t['type_id'],
                'count' => $t['count']
            ], $this->tables),
        ];

        $response = $this->repository->createBooking($payload);

        $this->handleApiResult($response,
            onSuccess: function () {
                $this->dispatch('swal:show', [
                    'type' => 'success',
                    'title' => __('bookings.success_message')
                ]);
                $this->reset(['startDate', 'startTime', 'duration', 'tables']);
                $this->dispatch('spa:navigate', [
                    'url' => route('user.bookings.history'),
                ]);
            },
            onFailure: function ($response) {
                $code = BookingErrorCode::tryFrom($response->errorCode ?? null);

                $message = match ($code) {
                    BookingErrorCode::PastBookingNotAllowed => __('bookings.past_time_error'),
                    BookingErrorCode::AlreadyHasActiveBooking => __('bookings.already_exists_message'),
                    BookingErrorCode::BookingCrossesMultipleDays => __('bookings.cross_day_error'),
                    BookingErrorCode::RestaurantClosedOnThatDay => __('bookings.closed_day_error'),
                    BookingErrorCode::BookingOutOfWorkingHours => __('bookings.out_of_hours_error'),
                    BookingErrorCode::NotEnoughTablesAvailable => __('bookings.not_enough_tables'),
                    BookingErrorCode::MaxPlacesExceeded => __('bookings.exceeds_max_places_short'),
                    default => __('common.something_went_wrong'),
                };

                $this->showErrorAlert($message);
            }
        );
    }

    public function render()
    {
        return view('livewire.user.restaurant-booking-form');
    }
}

