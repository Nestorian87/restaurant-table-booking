<form wire:submit="save">
    <div class="mb-3">
        <input type="file" class="form-control" wire:model="photo" accept="image/*" required>
        @error('photo') <span class="text-danger">@lang('common.something_went_wrong')</span> @enderror
    </div>

    <x-ui.button-green
        size="sm"
        as="button"
        type="submit">
        {{ __('common.upload') }}
    </x-ui.button-green>
</form>
