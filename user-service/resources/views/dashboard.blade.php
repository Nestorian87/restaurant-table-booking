@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 text-center">
                <h3 class="mb-4 text-success">{{ auth()->user()->name }} {{ auth()->user()->surname }}</h3>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-danger">@lang('auth.logout')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
