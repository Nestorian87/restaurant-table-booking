@props(['title', 'value', 'color' => 'success'])

<div class="col-md-4">
    <div class="card text-white bg-{{ $color }} h-100 shadow">
        <div class="card-body">
            <h5 class="card-title">{{ $title }}</h5>
            <p class="card-text fs-3">{{ $value }}</p>
        </div>
    </div>
</div>
