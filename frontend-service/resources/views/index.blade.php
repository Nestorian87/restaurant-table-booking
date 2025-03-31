@extends('layouts.app')

@section('content')
    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-success">@lang('index.title')</h1>
        <p class="lead text-muted">@lang('index.subtitle')</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
            <a href="{{ route('register') }}" class="btn btn-orange btn-lg px-5 shadow">
                @lang('index.get_started')
            </a>
            <a href="{{ route('login') }}" class="btn btn-success btn-lg px-5 shadow">
                @lang('index.login')
            </a>
        </div>
    </div>

    <div class="row align-items-center g-5">
        <div class="col-md-3">
            <img src="{{asset('img/index.png')}}" alt="Restaurant" class="img-fluid rounded-4 shadow-lg">
        </div>
        <div class="col-md-6">
            <h3 class="fw-bold mb-4">@lang('index.features_title')</h3>
            <div class="d-flex flex-column gap-3 fs-5">
                <div>
                    <strong class="d-block">@lang('index.feature_1')</strong>
                    <span class="text-muted">@lang('index.feature_1_desc')</span>
                </div>
                <div>
                    <strong class="d-block">@lang('index.feature_2')</strong>
                    <span class="text-muted">@lang('index.feature_2_desc')</span>
                </div>
                <div>
                    <strong class="d-block">@lang('index.feature_3')</strong>
                    <span class="text-muted">@lang('index.feature_3_desc')</span>
                </div>
                <div>
                    <strong class="d-block">@lang('index.feature_4')</strong>
                    <span class="text-muted">@lang('index.feature_4_desc')</span>
                </div>
                <div>
                    <strong class="d-block">@lang('index.feature_5')</strong>
                    <span class="text-muted">@lang('index.feature_5_desc')</span>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-5">

    <div class="row text-center mt-5">
        <div class="col-md-4">
            <h4 class="fw-bold">@lang('index.stat_title_1')</h4>
            <p class="text-muted">@lang('index.stat_1')</p>
        </div>
        <div class="col-md-4">
            <h4 class="fw-bold">@lang('index.stat_title_2')</h4>
            <p class="text-muted">@lang('index.stat_2')</p>
        </div>
        <div class="col-md-4">
            <h4 class="fw-bold">@lang('index.stat_title_3')</h4>
            <p class="text-muted">@lang('index.stat_3')</p>
        </div>
    </div>
@endsection
