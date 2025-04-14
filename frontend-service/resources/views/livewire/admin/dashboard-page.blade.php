<div>
    @include('components.layouts.partials.admin-header')

    <div class="container mt-5">

        <livewire:admin.dashboard-stats/>

        <div class="row mt-5">
            <div class="col-12 mb-3 d-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ __('admin.establishments') }}</h4>
                <a href="{{route('admin.restaurants.create') }}" class="btn btn-success">
                    {{ __('admin.add_restaurant') }}
                </a>
            </div>

            <livewire:admin.restaurants-grid/>
        </div>
    </div>
</div>
