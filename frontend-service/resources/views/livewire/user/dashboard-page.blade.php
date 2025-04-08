<div>
    <div class="container">
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="display-5 fw-bold">{{ __('user.dashboard_title') }}</h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person-circle me-1"></i> {{ __('user.profile') }}
                    </a>

                    <x-ui.button-red wire:click="logout" class="py-2 px-4">
                        {{ __('auth.logout') }}
                    </x-ui.button-red>
                </div>
            </div>
            <p class="text-muted">{{ __('user.dashboard_subtitle') }}</p>
        </div>

        <livewire:user.restaurants-grid />
    </div>
</div>
