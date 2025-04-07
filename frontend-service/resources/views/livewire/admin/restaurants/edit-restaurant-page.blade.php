<div class="container">
    <x-ui.button-green wire:click="goBack">
        ← {{ __('common.back') }}
    </x-ui.button-green>
    <div class="row mt-4">
        <div class="col-md-3">
            <ul class="list-group">
                <li class="list-group-item {{ $activeTab === 'settings' ? 'active' : '' }}"
                    wire:click="setTab('settings')">
                    {{ __('admin.settings') }}
                </li>
                <li class="list-group-item {{ $activeTab === 'gallery' ? 'active' : '' }}"
                    wire:click="setTab('gallery')">
                    {{ __('admin.gallery') }}
                </li>
                <li class="list-group-item {{ $activeTab === 'tables' ? 'active' : '' }}"
                    wire:click="setTab('tables')">
                    {{ __('admin.tables') }}
                </li>
                <li class="list-group-item {{ $activeTab === 'menu' ? 'active' : '' }}"
                    wire:click="setTab('menu')">
                    {{ __('admin.menu_management') }}
                </li>
                <li class="list-group-item {{ $activeTab === 'bookings' ? 'active' : '' }}"
                    wire:click="setTab('bookings')">
                    {{ __('admin.bookings') }}
                </li>
            </ul>
        </div>

        <div class="col-md-9">
            @if ($activeTab === 'settings')
                <livewire:admin.restaurants.tabs.settings-tab :restaurant-id="$restaurantId" />
            @elseif ($activeTab === 'gallery')
                <livewire:admin.restaurants.tabs.gallery-tab :restaurant-id="$restaurantId" />
            @elseif ($activeTab === 'tables')
                <livewire:admin.restaurants.tabs.tables-tab :restaurant-id="$restaurantId" />
            @elseif ($activeTab === 'menu')
                <livewire:admin.restaurants.tabs.menu-tab :restaurant-id="$restaurantId" />
            @elseif($activeTab === 'bookings')
                <livewire:admin.restaurants.tabs.bookings-tab :restaurant-id="$restaurantId" />
            @endif
        </div>
    </div>
</div>
