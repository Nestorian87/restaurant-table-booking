<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h2 class="text-center mb-4 text-success">{{ __('auth.login') }}</h2>

            <form wire:submit.prevent="login">

                <x-ui.input
                    name="email"
                    type="email"
                    label="{{ __('auth.email') }}"
                    :required="true"
                    model="email"
                    maxlength="254"
                />

                <x-ui.input
                    name="password"
                    type="password"
                    label="{{ __('auth.password') }}"
                    :required="true"
                    model="password"
                    maxlength="100"
                />

                <x-ui.button-green
                    type="submit"
                    as="button"
                    class="w-100">
                    {{ __('auth.login') }}
                </x-ui.button-green>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('register') }}" class="btn btn-link text-orange">
                    {{ __('auth.no_account') }}
                </a>
            </div>
        </div>
    </div>
</div>
