<div>
    <div class="container">

        <div class="mb-5">
            <div class="d-flex justify-content-between">
                <h1 class="display-5 fw-bold">{{ __('admin.dashboard_title') }}</h1>
                <x-ui.button-red wire:click="logout" class="align-self-center py-2 px-4">
                    {{ __('auth.logout') }}
                </x-ui.button-red>
            </div>
            <p class="text-muted">{{ __('admin.dashboard_subtitle') }}</p>
        </div>

        <livewire:admin.dashboard-stats/>

        <div class="row mt-5">
            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ __('admin.restaurants') }}</h4>
                <a href="{{route('admin.restaurants.create') }}" class="btn btn-success">
                    {{ __('admin.add_restaurant') }}
                </a>
            </div>

            <livewire:admin.restaurants-grid/>
        </div>
    </div>
</div>
