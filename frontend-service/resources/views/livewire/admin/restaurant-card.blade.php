<div class="{{ $class }}">
    <div class="card h-100 shadow">
        <div class="card-body">
            <h5 class="card-title">{{ $restaurant['name'] }}</h5>
            <p class="card-text text-muted">{{ $restaurant['location'] }}</p>
            <p class="card-text">{{ $restaurant['phone'] ?? '-' }}</p>

            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.restaurants.edit', $restaurant['id']) }}" class="btn btn-warning me-2">
                    {{ __('admin.edit') }}
                </a>
                <button class="btn btn-danger" wire:click="delete">
                    {{ __('admin.delete') }}
                </button>
            </div>
        </div>
    </div>
</div>
