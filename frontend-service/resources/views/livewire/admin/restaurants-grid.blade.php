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
    @foreach ($restaurants as $restaurant)
        <livewire:common.restaurant-card
            :restaurant="$restaurant"
            :wire:key="'restaurant-'.$restaurant['id']"
            :isAdmin="true"
            class="col-md-4"
        />
    @endforeach

    @if ($hasMorePages)
        <div x-init="observe($el)" class="text-center py-3">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    @endif
</div>

