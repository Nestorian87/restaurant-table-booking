<form wire:submit="submit" class="row gy-2 gx-2">
    <div class="col-md-6">
        <x-ui.input
            name="name"
            label="{{ __('admin.category_name') }}"
            model="name"
            required
            maxlength="255"
        />
    </div>
    <div class="col-md-3 d-flex align-items-end mb-3">
        <x-ui.button-green as="input" type="submit" class="">
            {{ $editId ? __('admin.update') : __('admin.add') }}
        </x-ui.button-green>
    </div>
</form>
