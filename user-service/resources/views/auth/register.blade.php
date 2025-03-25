@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4">
                <h2 class="text-center mb-4 text-orange">@lang('auth.register')</h2>

                <x-validation-errors/>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

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
                            <input name="password" type="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">@lang('auth.confirm_password')</label>
                            <input name="password_confirmation" type="password" class="form-control" minlength="8" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-orange w-100">@lang('auth.register')</button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ route('login.form') }}" class="btn btn-link text-success">@lang('auth.already_registered')</a>
                </div>
            </div>
        </div>
    </div>
@endsection
