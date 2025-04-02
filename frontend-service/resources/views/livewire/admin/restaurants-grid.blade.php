<div class="row g-3">
    @forelse ($restaurants as $restaurant)
        <livewire:admin.restaurant-card
            :restaurant="$restaurant"
            :wire:key="'restaurant-'.$restaurant['id']"
            class="col-md-4"
        />
    @empty
        <p class="text-muted">{{ __('admin.no_restaurants') }}</p>
    @endforelse
</div>
