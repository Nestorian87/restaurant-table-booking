<div>
    <livewire:admin.admin-header />

    <div class="container">
        <div class="mb-5">
            <h1 class="display-5 fw-bold">{{ __('admin.dashboard_title') }}</h1>
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
