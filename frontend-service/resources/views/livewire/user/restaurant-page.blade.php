<div class="container py-5">
    @include('components.layouts.partials.user-header')


    @if(!empty($restaurant['photos']))
        <div class="mb-5">
            <div class="swiper mySwiper rounded-4 shadow-sm overflow-hidden"
                 style="max-height: 500px; background: linear-gradient(135deg, #93e193, #e4f5e4);">
                <div class="swiper-wrapper">
                    @foreach ($restaurant['photos'] as $photo)
                        <div class="swiper-slide d-flex align-items-center justify-content-center"
                             style="height: 500px;">
                            <img src="{{ $photo['url'] }}"
                                 alt="Photo"
                                 class="img-fluid"
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev d-none d-md-block"></div>
                <div class="swiper-button-next d-none d-md-block"></div>
            </div>
        </div>
    @endif

    <div class="mb-5">
        <div class="row gy-4">
            <div class="col-md-6">
                <h1 class="fw-bold mb-3">{{ $restaurant['name'] }}</h1>

                @if($restaurant['has_menu'])
                    <a href="{{ route('user.restaurants.menu', ['restaurantId' => $restaurant['id']]) }}"
                       class="btn btn-success mb-3">
                        <i class="bi bi-list me-1"></i> {{ __('restaurant.view_menu') }}
                    </a>
                @endif

                <p class="text-muted">{{ $restaurant['description'] }}</p>

                @php
                    use Carbon\Carbon;

                    $now = Carbon::now($timezone);
                    $currentDay = ($now->dayOfWeekIso + 6) % 7;
                    $currentTime = $now->format('H:i:s');
                    $todaySchedule = collect($restaurant['working_hours'] ?? [])->firstWhere('day', $currentDay);
                    $isOpen = $todaySchedule && $currentTime >= $todaySchedule['open_time'] && $currentTime <= $todaySchedule['close_time'];
                @endphp

                <span
                    class="badge rounded-pill px-3 py-2 {{ $isOpen ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                    <i class="bi bi-circle-fill me-1 small"></i>
                    {{ $isOpen ? __('restaurant.open_now') : __('restaurant.closed_now') }}
                </span>

                @php
                    $working = collect($restaurant['working_hours']);
                    $grouped = $working->groupBy(fn($item) => $item['open_time'] . '-' . $item['close_time']);
                    $dayName = fn($day) => ucfirst(Carbon::now($timezone)->startOfWeek(Carbon::MONDAY)->addDays($day)->locale(app()->getLocale())->isoFormat('dddd'));

                    function formatDayRange(array $days, Closure $dayName): string {
                        sort($days);
                        $result = [];
                        $rangeStart = $rangeEnd = $days[0];
                        for ($i = 1; $i < count($days); $i++) {
                            if ($days[$i] === $rangeEnd + 1) {
                                $rangeEnd = $days[$i];
                            } else {
                                $result[] = $rangeStart === $rangeEnd ? $dayName($rangeStart) : $dayName($rangeStart) . ' – ' . $dayName($rangeEnd);
                                $rangeStart = $rangeEnd = $days[$i];
                            }
                        }
                        $result[] = $rangeStart === $rangeEnd ? $dayName($rangeStart) : $dayName($rangeStart) . ' – ' . $dayName($rangeEnd);
                        return implode(', ', $result);
                    }
                @endphp

                <div class="border rounded-4 shadow-sm bg-white mt-4">
                    <h6 class="fw-bold px-3 pt-3">{{ __('restaurant.working_hours') }}</h6>
                    <div class="px-3 pb-3">
                        @foreach ($grouped as $time => $days)
                            @php
                                $openClose = explode('-', $time);
                                $dayIndexes = $days->pluck('day')->sort()->values()->all();
                                $dayText = formatDayRange($dayIndexes, $dayName);
                            @endphp
                            <div
                                class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="text-muted"><i class="bi bi-calendar-week me-2"></i>{{ $dayText }}</div>
                                <span class="badge bg-light text-dark">{{ $openClose[0] }} – {{ $openClose[1] }}</span>
                            </div>
                        @endforeach
                        @php
                            $allDays = range(0, 6);
                            $workingDays = $working->pluck('day')->unique()->sort()->values()->all();
                            $closedDays = array_values(array_diff($allDays, $workingDays));
                            $closedText = count($closedDays) ? formatDayRange($closedDays, $dayName) : null;
                        @endphp

                        @if ($closedText)
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <div class="text-muted">
                                    <i class="bi bi-calendar-x me-2"></i>{{ $closedText }}
                                </div>
                                <span class="badge bg-light text-dark">{{ __('restaurant.closed') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @if($average && count($reviews) > 0)
                    @php
                        function badgeColor($rating) {
                            if ($rating >= 4) return 'success';
                            if ($rating >= 2) return 'warning';
                            return 'danger';
                        }
                    @endphp

                    <div class="bg-white rounded-4 shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0">{{ __('restaurant.total_rating') }}</h5>
                            <div class="d-flex align-items-center gap-2">
                                <x-ui.rating-stars :value="$average['total']" size="md"/>
                                <span class="badge bg-{{ badgeColor($average['total']) }} text-white px-3">
                        {{ $average['total'] }}/5
                    </span>
                            </div>
                        </div>

                        <div class="vstack">
                            @foreach (['kitchen', 'interior', 'service', 'atmosphere'] as $aspect)
                                <div
                                    class="d-flex justify-content-between align-items-center py-2  {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <span class="text-muted fw-semibold">{{ __('bookings.' . $aspect) }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <x-ui.rating-stars :value="$average[$aspect]" size="sm"/>
                                        <span class="badge bg-{{ badgeColor($average[$aspect]) }} text-white fw-medium">
                                            {{ $average[$aspect] }}/5
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BOOKING --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3">{{ __('restaurant.booking') }}</h2>
        @if ($activeBooking)
            <div class="alert alert-warning d-flex align-items-center gap-3 rounded-4 shadow-sm p-3">
                <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                <div>
                    <div class="fw-bold mb-1">{{ __('bookings.already_exists_title') }}</div>
                    <div class="text-muted">{{ __('bookings.already_exists_message') }}</div>
                </div>
            </div>
        @else
            <livewire:user.restaurant-booking-form :restaurant="$restaurant"/>
        @endif
    </div>

    <div class="mb-5">
        <h2 class="fw-bold mb-4">{{ __('restaurant.reviews') }}</h2>

        @forelse ($reviews as $review)
            <div class="border rounded-4 bg-white shadow-sm p-4 mb-4">
                {{-- Header: Avatar + Name + Date --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success-subtle rounded-circle d-flex justify-content-center align-items-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-person-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <div
                                class="fw-semibold text-dark">{{ $review['user']['name'] ?? __('bookings.deleted_account') }}</div>
                            <small
                                class="text-muted">{{ \Carbon\Carbon::parse($review['created_at'], $timezone)->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>

                {{-- Review Text --}}
                <p class="mb-3 fs-6 text-dark">{{ $review['text'] }}</p>

                {{-- Ratings --}}
                <div class="small text-muted mb-3 d-flex flex-wrap gap-2 align-items-center">
                    <div>{{ __('bookings.kitchen') }}:
                        <x-ui.rating-stars :value="$review['rating_kitchen']" size="xs"/>
                    </div>
                    <div>{{ __('bookings.interior') }}:
                        <x-ui.rating-stars :value="$review['rating_interior']" size="xs"/>
                    </div>
                    <div>{{ __('bookings.service') }}:
                        <x-ui.rating-stars :value="$review['rating_service']" size="xs"/>
                    </div>
                    <div>{{ __('bookings.atmosphere') }}:
                        <x-ui.rating-stars :value="$review['rating_atmosphere']" size="xs"/>
                    </div>
                </div>

                {{-- Reactions --}}
                <div class="d-flex gap-2 mt-2">
                    <button wire:click="toggleReaction({{ $review['id'] }}, 'like')"
                            class="btn btn-sm d-flex align-items-center gap-1 {{ $review['user_reaction'] === 'like' ? 'btn-success' : 'btn-outline-success' }}">
                        <i class="bi bi-hand-thumbs-up-fill"></i> {{ $review['likes'] ?? 0 }}
                    </button>
                    <button wire:click="toggleReaction({{ $review['id'] }}, 'dislike')"
                            class="btn btn-sm d-flex align-items-center gap-1 {{ $review['user_reaction'] === 'dislike' ? 'btn-danger' : 'btn-outline-danger' }}">
                        <i class="bi bi-hand-thumbs-down-fill"></i> {{ $review['dislikes'] ?? 0 }}
                    </button>
                </div>
            </div>
        @empty
            <p class="text-muted">{{ __('restaurant.no_reviews') }}</p>
        @endforelse
    </div>


</div>
<script>
    function initSwiper() {
        const swiperEl = document.querySelector('.mySwiper');
        const wrapper = swiperEl?.querySelector('.swiper-wrapper');
        const slide = swiperEl?.querySelector('.swiper-slide');

        if (!swiperEl || !wrapper || !slide) {
            return;
        }

        new Swiper('.mySwiper', {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    }

    document.addEventListener('livewire:initialized', () => {
        initSwiper();

        Livewire.hook('morphed', () => {
            initSwiper();
        });
    });
</script>
