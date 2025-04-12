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
                <livewire:common.menu-list
                    :menu-items="$menuItems"
                    :menu-categories="$menuCategories"
                    :selected-category-id="$selectedCategoryId"
                    :readonly="false"
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
                <livewire:common.menu-category-scroller
                    :menu-categories="$menuCategories"
                    :admin-mode="true"
                    wire:click.prevent
                    wire:key="menu-scroller-{{ $restaurantId }}"
                    wire:ignore.self
                />
            </div>
        @endif
    </div>
</div>
