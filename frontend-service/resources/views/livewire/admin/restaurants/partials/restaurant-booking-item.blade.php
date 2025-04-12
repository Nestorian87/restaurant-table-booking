@php use Carbon\Carbon; @endphp

<div class="col-12 col-md-6 col-lg-6">
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">
                    <i class="bi bi-calendar-event me-1 text-success"></i>
                    {{ Carbon::parse($booking['start_time'])->translatedFormat('d F Y, H:i') }}
                    →
                    {{ Carbon::parse($booking['end_time'])->translatedFormat('H:i') }}
                </h6>
                <span class="badge
                @if($booking['status'] === 'confirmed') bg-success
                @elseif($booking['status'] === 'cancelled') bg-secondary
                @else bg-secondary @endif
            ">
                <i class="bi bi-info-circle me-1"></i>
                {{ __('bookings.status_' . $booking['status']) }}
            </span>
            </div>

            <p class="mb-2">
                <i class="bi bi-person-circle me-1 text-secondary"></i>
                <strong>{{ __('bookings.user') }}:</strong>
                {{ $booking['user']['name'] }} {{ $booking['user']['surname'] }}
            </p>

            <ul class="list-unstyled small mb-2">
                @foreach($booking['table_types'] as $type)
                    <li>
                        <i class="bi bi-grid-3x3-gap me-1 text-muted"></i>
                        {{ $type['places_count'] }} {{ trans_choice('bookings.places_word', $type['places_count']) }} –
                        {{ $type['pivot']['tables_count'] }} {{ trans_choice('bookings.tables_word',$type['pivot']['tables_count']) }}
                    </li>
                @endforeach
            </ul>

            @if($booking['review'])
                <div class="alert alert-light border mt-2 small">
                    <div>
                        <i class="bi bi-chat-left-text me-1 text-info"></i>
                        <strong>{{ __('bookings.review') }}:</strong> {{ $booking['review']['text'] }}
                    </div>
                    <div class="mt-1 text-muted">
                        <i class="bi bi-star me-1"></i>
                        {{ __('bookings.kitchen') }}: {{ $booking['review']['rating_kitchen'] }},
                        {{ __('bookings.interior') }}: {{ $booking['review']['rating_interior'] }},
                        {{ __('bookings.service') }}: {{ $booking['review']['rating_service'] }},
                        {{ __('bookings.atmosphere') }}: {{ $booking['review']['rating_atmosphere'] }}
                    </div>
                </div>
            @endif

            @if($booking['status'] === 'confirmed')
                <x-ui.button-red size="sm" class="mt-3" wire:click="cancel" padding="">
                    {{ __('bookings.cancel_booking') }}
                </x-ui.button-red>
            @endif
        </div>
    </div>
</div>
