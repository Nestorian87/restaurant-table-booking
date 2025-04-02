@php use Carbon\Carbon; @endphp
<div>
    <h5 class="fw-bold mb-4">{{ __('admin.working_hours') }}</h5>

    <div class="table-responsive">
        <table class="table align-middle table-bordered shadow-sm">
            <thead class="table-light text-center">
            <tr>
                <th>{{ __('admin.day') }}</th>
                <th>{{ __('admin.is_working_day') }}</th>
                <th>{{ __('admin.open_time') }}</th>
                <th>{{ __('admin.close_time') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach (range(0, 6) as $day)
                @php
                    $weekday = Carbon::create()->startOfWeek()->addDays($day)->locale(app()->getLocale())->isoFormat('dddd');
                    $dayKey = "day_" . $day;
                    $dayValues = $hours[$dayKey] ?? ['active' => false, 'open_time' => '', 'close_time' => ''];
                @endphp

                <livewire:admin.working-hours-row
                    :day="$day"
                    :weekdayName="$weekday"
                    :values="$dayValues"
                    wire:key="working-hour-row-{{ $day }}"
                />
            @endforeach
            </tbody>

        </table>
    </div>
</div>
