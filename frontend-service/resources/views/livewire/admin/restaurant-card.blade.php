<div class="{{ $class }}">
    <style>
        .restaurant-card {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .restaurant-card:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .image-hover {
            transition: transform 0.4s ease;
        }

        .restaurant-card:hover .image-hover {
            transform: scale(1.05);
        }
    </style>
    <a href="{{ route('admin.restaurants.edit', $restaurant['id']) }}" class="text-decoration-none text-dark">
        <div class="card h-100 border-0 shadow-sm transition restaurant-card">
            <div class="ratio ratio-16x9 rounded-top overflow-hidden position-relative">
                @if(!empty($restaurant['photos']))
                    <img src="{{ $restaurant['photos'][0]['url'] }}"
                         class="w-100 h-100 object-fit-cover image-hover"
                         alt="{{ $restaurant['name'] }}">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                        <i class="bi bi-card-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
            </div>

            <div class="card-body">
                <h5 class="card-title mb-1">{{ $restaurant['name'] }}</h5>
                <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill me-1"></i> {{ $restaurant['location'] }}</p>
                <p class="text-muted mb-0"><i class="bi bi-telephone-fill me-1"></i> {{ $restaurant['phone'] ?? '-' }}
                </p>
            </div>
        </div>
    </a>
</div>
