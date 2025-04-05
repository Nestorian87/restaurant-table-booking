<form wire:submit="save">
    <div class="mb-3">
        <input type="file" class="form-control" wire:model="photo" required>
        @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <x-ui.button-green
        size="sm"
        as="button"
        type="submit">
        {{ __('common.upload') }}
    </x-ui.button-green>
</form>
