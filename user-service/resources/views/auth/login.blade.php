@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-success">@lang('auth.login')</h2>

                <x-validation-errors />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">@lang('auth.email')</label>
                        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">@lang('auth.password')</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">@lang('auth.login')</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('register.form') }}" class="btn btn-link text-orange">@lang('auth.no_account')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
