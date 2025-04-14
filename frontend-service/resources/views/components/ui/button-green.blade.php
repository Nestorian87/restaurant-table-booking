@props([
    'as' => 'a',
    'href' => '#',
    'type' => 'button',
    'size' => 'md',
    'shadow' => false,
    'padding' => 'px-5',
    'disabled' => false,
])

@php
    $class = "btn btn-success btn-{$size} {$padding}";
    if ($shadow) $class .= ' shadow';
    if ($disabled) $class .= ' disabled';
    if ($attributes->get('class')) $class .= ' ' . $attributes->get('class');
@endphp

@if ($as === 'a')
    <a href="{{ $disabled ? 'javascript:void(0)' : $href }}"
       class="{{ $class }}"
       {{ $attributes->except(['class', 'href']) }}
       @if($disabled) aria-disabled="true" tabindex="-1" @endif
        {{ $attributes->except('class') }}>
        {{ $slot }}
    </a>
@elseif ($as === 'button')
    <button type="{{ $type }}"
            class="{{ $class }}"
            @if($disabled) disabled @endif
        {{ $attributes->except(['class', 'type']) }}>
        {{ $slot }}
    </button>
@elseif ($as === 'input')
    <input type="{{ $type }}"
           class="{{ $class }}"
           value="{{ trim($slot) }}"
           @if($disabled) disabled @endif
        {{ $attributes->except(['class', 'type']) }}>
@endif
