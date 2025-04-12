@php
    $fullStars = floor($value);
    $halfStar = $value - $fullStars >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    $sizeClass = match($size) {
        'sm' => 'fs-6',
        'md' => 'fs-5',
        'lg' => 'fs-4',
        'xl' => 'fs-3',
        'xs' => 'fs-6 text-muted',
        default => 'fs-5'
    };
@endphp

<div class="d-inline-flex align-items-center gap-1 {{ $sizeClass }}">
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="bi bi-star-fill text-warning"></i>
    @endfor

    @if ($halfStar)
        <i class="bi bi-star-half text-warning"></i>
    @endif

    @for ($i = 0; $i < $emptyStars; $i++)
        <i class="bi bi-star text-warning"></i>
    @endfor
</div>
