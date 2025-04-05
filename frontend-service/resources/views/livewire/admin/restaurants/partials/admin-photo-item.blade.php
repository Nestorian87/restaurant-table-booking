<div class="col">
    <div class="card h-100 shadow-sm border-0">
        <div class="ratio ratio-4x3">
            <img src="{{ $photo['url'] }}" class="object-fit-cover w-100 h-100" alt="Photo">
        </div>
        <div class="card-body text-center p-2">
            <x-ui.button-red
                size="sm"
                padding=""
                wire:click="delete">
                {{ __('common.delete') }}
            </x-ui.button-red>

        </div>
    </div>
</div>
