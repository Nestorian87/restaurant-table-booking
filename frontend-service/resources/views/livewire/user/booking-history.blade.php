@php use Carbon\Carbon; @endphp

<div class="container py-5">
    @include('components.layouts.partials.user-header')

    <h2 class="fw-bold mb-4">{{ __('bookings.history') }}</h2>

    @forelse ($bookings as $booking)
        <div class="card border-0 shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $booking['restaurant']['name'] ?? __('bookings.deleted_restaurant') }}</h5>
                        <small class="text-muted">
                            {{ __('bookings.date') }}:
                            {{ Carbon::parse($booking['start_time'], $timezone)->translatedFormat('d F Y, H:i') }}
                            –
                            {{ Carbon::parse($booking['end_time'], $timezone)->format('H:i') }}
                        </small>
                    </div>

                    @php
                        $status = $booking['status'];
                        $statusClass = match($status) {
                            'confirmed' => 'bg-success-subtle text-success-emphasis',
                            'cancelled' => 'bg-danger-subtle text-danger-emphasis',
                            default => 'bg-light text-dark',
                        };
                    @endphp

                    <span class="badge rounded-pill text-uppercase px-3 py-2 {{ $statusClass }}">
                        {{ __('bookings.status_' . $status) }}
                    </span>
                </div>

                @php
                    $end = Carbon::parse($booking['end_time'], $timezone);
                    $now = Carbon::now($timezone);
                    $isCompleted = $booking['status'] === 'confirmed' && $end->isPast();
                    $isReviewed = !empty($booking['review']);
                @endphp

                <div class="mt-3">
                    @if ($booking['status'] === 'confirmed' && !$isCompleted)
                        <x-ui.button-red as="button" size="sm" wire:click="cancelBooking({{ $booking['id'] }})">
                            <i class="bi bi-x-lg me-1"></i> {{ __('common.cancel') }}
                        </x-ui.button-red>

                    @elseif ($isCompleted && !$isReviewed)
                        <x-ui.button-green as="button" size="sm" wire:click="startReview({{ $booking['id'] }})">
                            <i class="bi bi-star me-1"></i> {{ __('bookings.leave_review') }}
                        </x-ui.button-green>

                    @elseif ($isReviewed)
                        <div class="mt-3 bg-light-subtle border rounded-4 p-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <span class="fw-semibold text-success-emphasis">
                                    {{ __('bookings.reviewed') }}
                                </span>
                            </div>

                            @if(!empty($booking['review']['text']))
                                <p class="mb-3 text-dark fst-italic">{{ $booking['review']['text'] }}</p>
                            @endif

                            <div class="d-flex flex-column flex-sm-row gap-3 small text-muted">
                                <div>{{ __('bookings.kitchen') }}:
                                    <x-ui.rating-stars :value="$booking['review']['rating_kitchen']" size="xs"/>
                                </div>
                                <div>{{ __('bookings.interior') }}:
                                    <x-ui.rating-stars :value="$booking['review']['rating_interior']" size="xs"/>
                                </div>
                                <div>{{ __('bookings.service') }}:
                                    <x-ui.rating-stars :value="$booking['review']['rating_service']" size="xs"/>
                                </div>
                                <div>{{ __('bookings.atmosphere') }}:
                                    <x-ui.rating-stars :value="$booking['review']['rating_atmosphere']" size="xs"/>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info rounded-4 shadow-sm">
            <i class="bi bi-info-circle me-1"></i> {{ __('bookings.no_bookings') }}
        </div>
    @endforelse

    {{-- MODAL: Leave Review --}}
    <x-ui.modal title="{{ __('bookings.leave_review') }}" wire:model="reviewingId">
        <form wire:submit.prevent="submitReview" class="vstack gap-3" x-show="show" x-transition>
            @foreach(['kitchen', 'interior', 'service', 'atmosphere'] as $aspect)
                <div>
                    <label class="form-label fw-semibold">{{ __('bookings.' . $aspect) }}</label>
                    <x-ui.rating-stars-input wire:model="review.rating_{{ $aspect }}"/>
                </div>
            @endforeach

            <div>
                <label class="form-label fw-semibold">{{ __('bookings.comment') }}</label>
                <textarea class="form-control rounded-3" wire:model.defer="review.text" rows="3"
                          placeholder="{{ __('bookings.enter_comment') }}"
                          required maxlength="400"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <x-ui.button-red as="button" wire:click="$set('reviewingId', 0)">
                    {{ __('common.cancel') }}
                </x-ui.button-red>
                <x-ui.button-green as="button" type="submit">
                    <i class="bi bi-check-circle me-1"></i> {{ __('bookings.submit') }}
                </x-ui.button-green>
            </div>
        </form>
    </x-ui.modal>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        Livewire.dispatch('user-timezone', { timezone });
    })
</script>
