<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">{{ __('admin.menu_management') }}</h5>

        <div class="d-flex gap-2">
            <x-ui.button-green wire:click="changePage('category-form')">
                + {{ __('admin.add_menu_category') }}
            </x-ui.button-green>
            <x-ui.button-orange wire:click="changePage('item-form')">
                + {{ __('admin.add_menu_item') }}
            </x-ui.button-orange>
        </div>
    </div>

    <div class="row">
        <div class="{{ $page === 'list' ? 'col-md-9' : 'col-12' }}">
            @if($page === 'list')
                <livewire:admin.restaurants.tabs.menu-list
                    :menu-items="$menuItems"
                    :menu-categories="$menuCategories"
                    :selected-category-id="$selectedCategoryId"
                />
            @elseif($page === 'category-form')
                <div class="mb-3">
                    <x-ui.button-green wire:click="changePage('list')">
                        ← {{ __('common.back') }}
                    </x-ui.button-green>
                </div>
                <livewire:admin.restaurants.tabs.menu-category-form
                    :editing="$editingCategory"
                    :restaurant-id="$restaurantId"/>
            @elseif($page === 'item-form')
                <div class="mb-3">
                    <x-ui.button-green wire:click="changePage('list')">
                        ← {{ __('common.back') }}
                    </x-ui.button-green>
                </div>
                <livewire:admin.restaurants.tabs.menu-item-form
                    :restaurant-id="$restaurantId"
                    :menu-categories="$menuCategories"
                    :editing="$editingItem"
                />
            @endif
        </div>

        @if($page === 'list')
            <div class="col-md-3 ps-3 border-start">
                <div class="list-group sticky-top" style="top: 80px;" x-data
                     x-init="$watch('$store.menuScroll.activeCategoryId', val => active = val)">
                    @foreach($menuCategories as $cat)
                        <div class="list-group-item d-flex justify-content-between align-items-center"
                             :class="{ 'active': $store.menuScroll.activeCategoryId === {{ $cat['id'] }} }"
                        >
                            <button
                                type="button"
                                class="btn btn-sm border-0 p-0 text-start flex-grow-1 text-truncate bg-transparent"
                                :class="$store.menuScroll.activeCategoryId === {{ $cat['id'] }} ? 'text-white' : 'text-body'"
                                @click="$store.menuScroll.scrollToCategory({{ $cat['id'] }})"
                            >
                                {{ $cat['name'] }}
                            </button>

                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-link p-0 text-warning"
                                        wire:click="editCategory({{ $cat['id'] }}, '{{ addslashes($cat['name']) }}')"
                                        title="{{ __('admin.edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-link p-0 text-danger"
                                        wire:click="confirmDeleteCategory({{ $cat['id'] }})"
                                        title="{{ __('admin.delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


    </div>
</div>
