<form wire:submit.prevent="save" class="vstack gap-4 p-4 bg-white rounded-4 shadow-sm">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('bookings.date') }}</label>
            <input type="date" class="form-control rounded-3" required wire:model.live="startDate">
            <div class="invalid-feedback">{{ __('bookings.past_time_error') }}</div>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">{{ __('bookings.time') }}</label>
            <input type="time" class="form-control rounded-3" required wire:model.live="startTime">
            <div class="invalid-feedback">{{ __('bookings.past_time_error') }}</div>
        </div>
    </div>

    @php
        use Carbon\Carbon;

        $canSelectTables = $startDate && $startTime && $duration && count($availableTableTypes) > 0;
        $selectedIds = array_column($tables, 'type_id');
        $hasDateTime = $startDate && $startTime;
        $workingDay = false;
        $workingTimeValid = false;
        $hasAvailableTables = count($availableTableTypes) > 0;
        $closingSoon = false;

        if ($hasDateTime) {
            try {
                $start = Carbon::parse("{$startDate} {$startTime}");
                $end = (clone $start)->addMinutes((int) $duration);

                $weekday = ($start->dayOfWeekIso + 6) % 7;
                $todaySchedule = collect($restaurant['working_hours'])->firstWhere('day', $weekday);

                if ($todaySchedule) {
                    $workingDay = true;

                    $openTime = Carbon::parse("{$startDate} {$todaySchedule['open_time']}");
                    $closeTime = Carbon::parse("{$startDate} {$todaySchedule['close_time']}");

                    $workingTimeValid = $start->gte($openTime) && $end->lte($closeTime);

                    $minutesToClose = abs($closeTime->diffInMinutes($start));
                    if ($minutesToClose >= 0 && $minutesToClose < 30) {
                        $closingSoon = true;
                    }
                }
            } catch (\Exception $e) {
                $workingDay = false;
            }
        }

        $canSubmit = $hasDateTime && $workingDay && $workingTimeValid && !$closingSoon && $hasAvailableTables && count($tables) > 0;
    @endphp

    @if(!empty($availableDurations))
        <div>
            <label class="form-label fw-semibold">{{ __('bookings.duration') }}</label>
            <select class="form-select rounded-3" wire:model.live="duration"
                    wire:key="{{ implode(',', array_keys($availableDurations)) }}">
                @forelse($availableDurations as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @empty
                    <option disabled>{{ __('bookings.select_time_first') }}</option>
                @endforelse
            </select>
        </div>
    @endif

    @if (!$hasDateTime)
        <div class="alert alert-info rounded-3">
            <i class="bi bi-clock-history me-1"></i>
            {{ __('bookings.select_date_time_first') }}
        </div>
    @elseif (!$workingDay)
        <div class="alert alert-warning rounded-3">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ __('bookings.closed_day_error') }}
        </div>
    @elseif (!$workingTimeValid)
        <div class="alert alert-warning rounded-3">
            <i class="bi bi-clock me-1"></i>
            {{ __('bookings.out_of_hours_error') }}
        </div>
    @elseif ($closingSoon)
        <div class="alert alert-warning rounded-3">
            <i class="bi bi-clock me-1"></i>
            {{ __('bookings.closing_soon_warning') }}
        </div>
    @elseif (!$hasAvailableTables)
        <div class="alert alert-danger rounded-3">
            <i class="bi bi-x-octagon me-1"></i>
            {{ __('bookings.no_tables_available') }}
        </div>
    @endif

    @if($canSelectTables)
        <div class="border rounded-4 p-3 bg-light-subtle">
            <label class="form-label fw-semibold">{{ __('bookings.add_table_type') }}</label>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <select class="form-select w-50 rounded-3" wire:model.defer="newTableTypeId"
                        wire:key="table-type-select-{{ implode('-', array_column($availableTableTypes, 'id')) }}-{{ implode('-', $selectedIds) }}">
                    @foreach ($availableTableTypes as $type)
                        @if(!in_array($type['id'], $selectedIds))
                            <option value="{{ $type['id'] }}">
                                {{ $type['places_count'] }} {{ __('bookings.places_table') }}
                                ({{ __('bookings.available') }}: {{ $type['available'] }})
                            </option>
                        @endif
                    @endforeach
                </select>

                <input type="number" class="form-control w-25 rounded-3" wire:model="newTableCount" min="1" max="100"
                       placeholder="{{ __('bookings.count') }}" required>

                <x-ui.button-green as="button" wire:click="addTableType"> {{ __('bookings.add') }}</x-ui.button-green>
            </div>
        </div>


        <div>
            <label class="form-label fw-semibold">{{ __('bookings.selected_tables') }}</label>
            @forelse ($tables as $index => $table)
                @php
                    $type = collect($restaurant['table_types'])->firstWhere('id', $table['type_id']);
                @endphp
                <div class="d-flex gap-2 align-items-center mb-2">
                    <div class="form-control w-50 bg-light rounded-3">
                        {{ $type['places_count'] }} {{ __('bookings.places_table') }}
                    </div>
                    <div class="form-control w-25 bg-light text-center rounded-3">
                        {{ $table['count'] }}
                    </div>
                    <x-ui.button-red as="button" padding="" wire:click="removeTableType({{ $index }})">
                        <i class="bi bi-x-lg"></i>
                    </x-ui.button-red>
                </div>
            @empty
                <p class="text-muted">{{ __('bookings.no_tables_selected') }}</p>
            @endforelse
        </div>
    @endif


    <x-ui.button-green as="button" type="submit" disabled="{{ !$canSubmit }}">
        <i class="bi bi-calendar-check me-1"></i> {{ __('bookings.book_now') }}
    </x-ui.button-green>
</form>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.querySelector('input[type="date"][wire\\:model\\.live="startDate"]');
        const timeInput = document.querySelector('input[type="time"][wire\\:model\\.live="startTime"]');

        function roundUpTo30Minutes(date) {
            const ms = 1000 * 60 * 30;
            return new Date(Math.ceil(date.getTime() / ms) * ms);
        }

        function setMinDate() {
            dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);
        }

        function autoCorrectIfPast() {
            if (!dateInput.value || !timeInput.value) return;

            const selected = new Date(`${dateInput.value}T${timeInput.value}`);
            const now = new Date();

            if (selected < now) {
                const rounded = roundUpTo30Minutes(now);
                const localDate = rounded.toISOString().slice(0, 10);
                const localTime = rounded.toTimeString().slice(0, 5);

                dateInput.value = localDate;
                timeInput.value = localTime;

                dateInput.dispatchEvent(new Event('input', {bubbles: true}));
                timeInput.dispatchEvent(new Event('input', {bubbles: true}));
            }
        }

        setMinDate();
        dateInput.addEventListener('change', autoCorrectIfPast);
        timeInput.addEventListener('change', autoCorrectIfPast);

        Livewire.hook('morphed', setMinDate);
    });

    document.addEventListener('livewire:initialized', () => {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        Livewire.dispatch('user-timezone', {timezone});
    })
</script>


