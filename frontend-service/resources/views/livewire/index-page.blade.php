<div>
    <div class="text-center mb-5">
        <h1 class="display-3 fw-bold text-success">@lang('index.title')</h1>
        <p class="lead text-muted">@lang('index.subtitle')</p>

        <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
            <x-ui.button-orange :href="route('register')" size="lg" shadow>
                @lang('index.get_started')
            </x-ui.button-orange>

            <x-ui.button-green :href="route('login')" size="lg" shadow>
                @lang('index.login')
            </x-ui.button-green>
        </div>
    </div>

    <div class="row align-items-center g-5">
        <div class="col-md-3">
            <img src="{{ asset('img/index.png') }}" alt="Restaurant" class="img-fluid rounded-4 shadow-lg">
        </div>
        <div class="col-md-6">
            <h3 class="fw-bold mb-4">@lang('index.features_title')</h3>
            <div class="d-flex flex-column gap-3 fs-5">
                @foreach (range(1, 5) as $i)
                    <div>
                        <strong class="d-block">@lang("index.feature_{$i}")</strong>
                        <span class="text-muted">@lang("index.feature_{$i}_desc")</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row text-center mt-5">
        @foreach (range(1, 3) as $i)
            <div class="col-md-4">
                <h4 class="fw-bold">@lang("index.stat_title_{$i}")</h4>
                <p class="text-muted">@lang("index.stat_{$i}")</p>
            </div>
        @endforeach
    </div>
</div>
