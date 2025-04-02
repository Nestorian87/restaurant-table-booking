<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card p-4">
            <h2 class="text-center mb-4 text-orange">{{ __('auth.register') }}</h2>

            <form wire:submit="register">
                <div class="row">
                    <div class="col-md-6">
                        <x-ui.input
                            name="name"
                            type="text"
                            label="{{ __('auth.name') }}"
                            :required="true"
                            model="name"
                            maxlength="100"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-ui.input
                            name="surname"
                            type="text"
                            label="{{ __('auth.surname') }}"
                            :required="true"
                            model="surname"
                            maxlength="100"
                        />
                    </div>
                </div>

                <x-ui.input
                    name="email"
                    type="email"
                    label="{{ __('auth.email') }}"
                    :required="true"
                    model="email"
                    maxlength="254"
                />

                <div class="row">
                    <div class="col-md-6">
                        <x-ui.input
                            name="password"
                            type="password"
                            label="{{ __('auth.password') }}"
                            :required="true"
                            model="password"
                            maxlength="100"
                        />
                    </div>
                    <div class="col-md-6">
                        <x-ui.input
                            name="password_confirmation"
                            type="password"
                            label="{{ __('auth.confirm_password') }}"
                            :required="true"
                            model="password_confirmation"
                            maxlength="100"
                        />
                    </div>
                </div>

                <x-ui.button-orange type="submit" as="button" class="w-100">
                    {{ __('auth.register') }}
                </x-ui.button-orange>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="btn btn-link text-success">
                    {{ __('auth.already_registered') }}
                </a>
            </div>
        </div>
    </div>
</div>
