<div>
    <div class="row mb-4 g-2 align-items-center">
        <div class="col-auto">
            <label class="form-label">{{ __('bookings.filter_status') }}</label>
            <select wire:model.live="status" class="form-select form-select-sm">
                <option value="">{{ __('bookings.all_statuses') }}</option>
                <option value="confirmed">{{ __('bookings.status_confirmed') }}</option>
                <option value="cancelled">{{ __('bookings.status_cancelled') }}</option>
            </select>
        </div>

        <div class="col-auto">
            <label class="form-label">{{ __('bookings.sort_by') }}</label>
            <select wire:model.live="sortBy" class="form-select form-select-sm">
                <option value="start_time">{{ __('bookings.sort_start_time') }}</option>
                <option value="end_time">{{ __('bookings.sort_end_time') }}</option>
                <option value="status">{{ __('bookings.sort_status') }}</option>
                <option value="created_at">{{ __('bookings.sort_created_at') }}</option>
            </select>
        </div>

        <div class="col-auto">
            <label class="form-label">{{ __('bookings.sort_direction') }}</label>
            <select wire:model.live="sortDir" class="form-select form-select-sm">
                <option value="desc">{{ __('bookings.sort_desc') }}</option>
                <option value="asc">{{ __('bookings.sort_asc') }}</option>
            </select>
        </div>
    </div>

    <div class="row g-3"
         x-data="{
            observe(el) {
                let observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $wire.loadMore()
                        }
                    })
                }, {
                    threshold: 1.0
                })
                observer.observe(el)
            }
        }">
        @foreach ($bookings as $booking)
            <livewire:admin.restaurants.partials.restaurant-booking-item
                :booking="$booking"
                :restaurant-id="$restaurantId"
                :key="$booking['id']"
            />
        @endforeach

        @if ($hasMorePages)
            <div x-init="observe($el)" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('bookings.loading') }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
