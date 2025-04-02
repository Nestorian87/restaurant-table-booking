<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="{{ route('admin.dashboard') }}">
            Admin Panel
        </a>

        <div class="d-flex ms-auto">
            <button class="btn btn-outline-danger" wire:click="logout">
                {{ __('auth.logout') }}
            </button>
        </div>
    </div>
</nav>
