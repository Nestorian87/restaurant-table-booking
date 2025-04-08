<div class="container py-5">
    <x-ui.button-green wire:click="goBack">
        ← {{ __('common.back') }}
    </x-ui.button-green>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h2 class="text-center mb-4 fw-bold">{{ __('user.edit_profile') }}</h2>

                <form wire:submit="save" class="row">
                    <div class="col-12">
                        <x-ui.input
                            name="name"
                            type="text"
                            label="{{ __('auth.name') }}"
                            :required="true"
                            model="name"
                            maxlength="100"
                        />
                    </div>

                    <div class="col-12">
                        <x-ui.input
                            name="surname"
                            type="text"
                            label="{{ __('auth.surname') }}"
                            :required="true"
                            model="surname"
                            maxlength="100"
                        />
                    </div>

                    <div class="col-12">
                        <x-ui.input
                            name="email"
                            type="email"
                            label="{{ __('auth.email') }}"
                            :required="true"
                            model="email"
                            maxlength="254"
                        />
                    </div>

                    <div class="col-12">
                        <x-ui.input
                            name="password"
                            type="password"
                            margin="mb-1"
                            label="{{ __('auth.password') }}"
                            :required="!empty($password_confirmation)"
                            modelLive="password"
                            maxlength="100"
                        />
                    </div>
                    <div class="col-12">
                        <p class="text-muted small mb-2">
                            {{ __('user.password_hint') }}
                        </p>
                    </div>

                    <div class="col-12">
                        <x-ui.input
                            name="password_confirmation"
                            type="password"
                            label="{{ __('auth.confirm_password') }}"
                            :required="!empty($password)"
                            modelLive="password_confirmation"
                            maxlength="100"
                        />
                    </div>

                    <div class="col-12 mt-3">
                        <x-ui.button-green type="submit" as="button" size="md" class="py-2 w-100">
                            {{ __('common.save') }}
                        </x-ui.button-green>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
