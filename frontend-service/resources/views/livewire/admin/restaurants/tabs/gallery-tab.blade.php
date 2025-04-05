<div class="card shadow">
    <div class="card-body">
        <h5 class="card-title">{{ __('admin.gallery') }}</h5>

        <livewire:admin.restaurants.partials.gallery-upload-form :restaurant-id="$restaurantId" />

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 mt-4 g-3">
            @foreach($photos as $photo)
                <livewire:admin.restaurants.partials.admin-photo-item :photo="$photo" :wire:key="$photo['id']" />
            @endforeach
        </div>
    </div>
</div>
