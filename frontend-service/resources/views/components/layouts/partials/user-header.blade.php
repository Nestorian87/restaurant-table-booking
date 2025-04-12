<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">

        {{-- Brand --}}
        <a class="navbar-brand fw-semibold text-dark" href="{{ route('home') }}">
            {{ __('common.app_name') }}
        </a>

        {{-- Right actions --}}
        <div class="d-flex align-items-center gap-3">
            {{-- Booking History --}}
            <a href="{{ route('user.bookings.history') }}" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-clock-history me-1"></i> {{ __('bookings.history') }}
            </a>

            {{-- Profile --}}
            <a href="{{ route('user.profile') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-person-circle me-1"></i> {{ __('user.profile') }}
            </a>

            {{-- Logout --}}
            <form wire:submit.prevent="logout" class="d-inline">
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i> {{ __('auth.logout') }}
                </button>
            </form>

            {{-- Language switcher --}}
            @include('components.layouts.partials.language-switcher')
        </div>
    </div>
</nav>
