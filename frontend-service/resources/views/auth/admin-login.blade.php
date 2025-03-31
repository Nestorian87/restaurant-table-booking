@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-success">@lang('auth.admin_login')</h2>

                <form id="admin-login-form">
                    <div class="mb-3">
                        <label for="email" class="form-label">@lang('auth.email')</label>
                        <input name="email" type="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">@lang('auth.password')</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">@lang('auth.login')</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="btn btn-link text-orange">@lang('auth.user_login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/constants.js') }}"></script>
    <script>
        document.getElementById('admin-login-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;

            const data = {
                email: form.email.value,
                password: form.password.value
            };

            try {
                const response = await fetch(`${AppConfig.apiUrl}/users/admin/login`, {
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

                        case UserErrorCode.Unauthorized:
                            Swal.fire({
                                icon: 'error',
                                title: "@lang('common.error')",
                                text: "@lang('auth.invalid_credentials')"
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

                localStorage.setItem('admin_access_token', result.access_token);

                // Redirect or dashboard logic here
                // window.location.href = '/admin/dashboard';

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: "@lang('common.error')",
                    text: "@lang('common.something_went_wrong')"
                });
            }
        });
    </script>
@endsection
