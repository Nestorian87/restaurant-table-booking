<div>
    <form wire:submit.prevent="submit" class="row row-cols-1 row-cols-md-3 gy-3 gx-3 align-items-end mb-4">
        <div>
            <x-ui.input
                label="{{ __('admin.tables') }}"
                name="count"
                model="count"
                type="number"
                min="1"
                max="1000"
                :required="true"
            />
        </div>
        <div>
            <x-ui.input
                label="{{ __('admin.places') }}"
                name="places"
                modelLive="places"
                type="number"
                min="1"
                max="300"
                :required="true"
            />
        </div>
        <div class="d-grid mb-3">
            @if($editId)
                <x-ui.button-orange size="md" as="button" type="submit">
                    {{ __('admin.update') }}
                </x-ui.button-orange>
            @else
                <x-ui.button-green size="md" as="button" type="submit">
                    {{ __('admin.add') }}
                </x-ui.button-green>
            @endif
        </div>
    </form>

    <livewire:admin.restaurants.tabs.table-type-list
        :table-types="$tableTypes"
        wire:key="table-list"
    />
</div>
