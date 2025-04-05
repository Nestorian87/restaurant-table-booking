@props([
    'type' => 'text',
    'name',
    'label' => '',
    'min' => null,
    'max' => null,
    'maxlength' => null,
    'minlength' => null,
    'required' => false,
    'model' => null,
    'modelLive' => null,
    'wireInput' => null,
    'wireInputDebounce' => null,
    'pattern' => null,
    'placeholder' => null,
])

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    @endif

    <input
        @if ($model) wire:model.defer="{{ $model }}" @endif
        @if ($modelLive) wire:model.live="{{ $modelLive }}" @endif
        @if ($wireInput)
            {{ $wireInputDebounce ? 'wire:input.debounce.' . $wireInputDebounce : 'wire:input' }}="{{ $wireInput }}"
        @endif
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control"
        {{ $required ? 'required' : '' }}
        {{ $min ? 'min=' . $min : '' }}
        {{ $max ? 'max=' . $max : '' }}
        {{ $maxlength ? 'maxlength=' . $maxlength : '' }}
        {{ $minlength ? 'minlength=' . $minlength : '' }}
        {{ $placeholder ? 'placeholder=' . $placeholder : '' }}
        {{ $pattern ? 'pattern=' . $pattern : '' }}
    />
</div>
