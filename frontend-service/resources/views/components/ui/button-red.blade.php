@props([
    'as' => 'a',
    'href' => '#',
    'type' => 'button',
    'size' => 'md',
    'shadow' => false,
    'padding' => 'px-5'
])

@php
    $class = "btn btn-danger btn-{$size} {$padding}";
    if ($shadow) $class .= ' shadow';
    if ($attributes->get('class')) $class .= ' ' . $attributes->get('class');
@endphp

@if ($as === 'a')
    <a href="{{ $href }}" class="{{ $class }}" {{ $attributes->except('class') }}>
        {{ $slot }}
    </a>
@elseif ($as === 'button')
    <button type="{{ $type }}" class="{{ $class }}" {{ $attributes->except(['class', 'type']) }}>
        {{ $slot }}
    </button>
@elseif ($as === 'input')
    <input type="{{ $type }}" class="{{ $class }}" value="{{ trim($slot) }}" {{ $attributes->except(['class', 'type']) }}>
@endif
