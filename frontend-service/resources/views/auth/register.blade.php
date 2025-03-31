@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-orange">@lang('auth.register')</h2>

                <form id="register-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('auth.name')</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('auth.surname')</label>
                            <input name="surname" type="text" class="form-control" value="{{ old('surname') }}" maxlength="50" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">@lang('auth.email')</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" maxlength="50" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('auth.password')</label>
                            <input name="password" type="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('auth.confirm_password')</label>
                            <input name="password_confirmation" type="password" class="form-control" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-orange w-100">@lang('auth.register')</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="btn btn-link text-success">@lang('auth.already_registered')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/constants.js') }}"></script>
    <script>
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;

            const data = {
                name: form.name.value,
                surname: form.surname.value,
                email: form.email.value,
                password: form.password.value,
                password_confirmation: form.password_confirmation.value
            };

            try {
                if (data.password_confirmation !== data.password) {
                    Swal.fire({
                        icon: 'warning',
                        title: "@lang('common.error')",
                        text: "@lang('auth.passwords_do_not_match')"
                    });
                    return;
                }

                const response = await fetch(`${AppConfig.apiUrl}/users/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (!response.ok) {
                    switch (result.error_code) {
                        case UserErrorCode.ValidationFailed:
                            Swal.fire({
                                icon: 'warning',
                                title: "@lang('common.error')",
                                text: "@lang('common.validation_error')"
                            });
                            break;

                        case UserErrorCode.UserAlreadyExists:
                            Swal.fire({
                                icon: 'error',
                                title: "@lang('common.error')",
                                text: "@lang('auth.email_already_used')"
                            });
                            break;

                        case UserErrorCode.UnknownError:
                        default:
                            Swal.fire({
                                icon: 'error',
                                title: "@lang('common.error')",
                                text: "@lang('common.something_went_wrong')"
                            });
                            break;
                    }
                    return;
                }

                // Success: store the token and show confirmation
                localStorage.setItem('access_token', result.access_token);

                // Optional: redirect to dashboard or login
                // window.location.href = '/dashboard';

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "@lang('error')",
                    text: "@lang('something_went_wrong')"
                });
            }
        });
    </script>
@endsection

