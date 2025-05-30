@php use Illuminate\Support\Str; @endphp
<div class="col-md-12 pe-4" x-data="scrollSpy()" x-init="observe()">
    @forelse($menuCategories as $category)
        <div id="category-{{ $category['id'] }}" data-category-block class="mb-4" style="min-height: 200px;">
            <h5 class="d-flex align-items-center gap-2 py-3 px-1"
                style="font-size: 1.25rem; font-weight: 600; letter-spacing: -0.3px;">
                <i class="bi bi-tag-fill text-success" style="font-size: 1rem;"></i>
                <span class="text-dark text-truncate">{{ $category['name'] }}</span>
            </h5>

            @php
                $items = collect($menuItems)->where('menu_category_id', $category['id']);
            @endphp

            @forelse($items as $item)
                <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        {{-- Image or Placeholder --}}
                        <div class="col-md-4 d-flex align-items-stretch">
                            <div
                                class="h-100 w-100 d-flex align-items-center justify-content-center"
                                style="background-color: #f9f9f9; aspect-ratio: 4/3;">
                                @if(!empty($item['photo_url']))
                                    <img
                                        src="{{ $item['photo_url'] }}"
                                        alt="{{ $item['name'] }}"
                                        class="img-fluid w-100 h-100"
                                        style="object-fit: cover;"
                                    />
                                @else
                                    <i class="bi bi-card-image text-muted" style="font-size: 3rem;"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Info Section --}}
                        <div class="col-md-8">
                            <div class="card-body d-flex flex-column justify-content-between h-100 px-4 py-3">

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0 text-break fw-semibold" style="max-width: 75%;">
                                        {{ $item['name'] }}
                                    </h5>
                                    <div class="text-end fw-bold text-success" style="white-space: nowrap;">
                                        {{ $item['price'] }} ₴
                                    </div>
                                </div>

                                <div class="text-muted small mb-1">
                                    {{ $item['volume'] }} {{ __('units.' . $item['unit']) }}
                                </div>

                                <p class="card-text mb-3 text-muted" style="font-size: 0.925rem;">
                                    {{ Str::limit($item['description'], 100) }}
                                </p>

                                @if(!$readonly)
                                    <div class="mt-auto d-flex gap-2">
                                        <x-ui.button-orange size="sm"
                                                            wire:click="editItem({{ json_encode($item) }})">
                                            {{ __('admin.edit') }}
                                        </x-ui.button-orange>
                                        <x-ui.button-red size="sm"
                                                         wire:click="deleteItem({{ $item['id'] }})">
                                            {{ __('admin.delete') }}
                                        </x-ui.button-red>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">{{ __('admin.no_menu_items') }}</p>
            @endforelse
        </div>
    @empty
        <h6 class="text-center mt-4">{{ __('admin.no_menu_items') }}</h6>
    @endforelse
</div>



