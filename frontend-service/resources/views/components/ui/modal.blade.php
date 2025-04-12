@props([
    'title' => '',
    'wireModel' => $attributes->wire('model')->value(),
])

<div
    x-data="{ show: @entangle($wireModel).live }"
    x-show="show"
    x-transition.opacity
    x-cloak
    @keydown.escape.window="show = false"
    style="
        position: fixed;
        inset: 0;
        z-index: 1050;
        background-color: rgba(0, 0, 0, 0.5);
        overflow-y: auto;
        padding: 2rem 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
    "
>
    <div
        x-show="show"
        x-transition
        @click.away="show = false"
        style="
            background: white;
            border-radius: 1rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 1.5rem;
            margin: auto;
        "
    >
        {{-- Header --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h5 style="margin: 0; font-weight: bold;">{{ $title }}</h5>
            <button type="button" class="btn-close" @click="show = false" aria-label="Close"></button>
        </div>

        {{-- Body --}}
        <div class="modal-body">
            {{ $slot }}
        </div>
    </div>
</div>
