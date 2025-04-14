<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top rounded-0">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-success" href="{{ route('home') }}">
                @lang('common.app_name')
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active fw-bold text-success' : '' }}"
                           href="{{ route('user.dashboard') }}">
                            @lang('user.establishments')
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.bookings.history') ? 'active fw-bold text-success' : '' }}"
                           href="{{ route('user.bookings.history') }}"
                           wire:ignore>
                            @lang('user.bookings')
                        </a>
                    </li>

                    <li class="nav-item position-relative" wire:ignore>
                        <a class="nav-link {{ request()->routeIs('user.chat') ? 'active fw-bold text-success' : '' }}"
                           href="{{ route('user.chat') }}"
                           id="chat-link"
                           style="position: relative;">
                            @lang('user.chat')

                            <span id="chat-unread-badge"
                                  class="position-absolute badge rounded-pill bg-orange d-none"
                                  style="top: 0.45rem; right: 0.2rem; font-size: 0.7rem; transform: translate(50%, -50%);
                                   visibility: {{ request()->routeIs('user.chat') ? 'hidden' : 'visible' }}"></span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.profile') ? 'active fw-bold text-success' : '' }}"
                           href="{{ route('user.profile') }}">
                            @lang('user.profile')
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="{{ route('user.logout') }}">
                            @lang('auth.logout')
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    @include('components.layouts.partials.language-switcher')
                </div>
            </div>
        </div>
    </nav>
</header>
<livewire:user.unread-count-notifier />
