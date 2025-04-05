<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title fw-bold mb-4">{{ __('admin.tables') }}</h5>

        <form wire:submit.prevent="{{ $editId ? 'update' : 'add' }}" class="row row-cols-1 row-cols-md-3 gy-3 gx-3 align-items-end mb-4">
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

        <ul class="list-group">
            @foreach($tableTypes as $table)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $editId == $table['id'] ? 'bg-secondary-subtle' : '' }}">
                    <span>{{ $table['tables_count'] }} {{ trans_choice('admin.tables_word', $table['tables_count']) }} × {{ $table['places_count'] }} {{  trans_choice('admin.places_word', $table['places_count']) }}</span>
                    <div class="d-flex gap-2">
                        <x-ui.button-orange size="sm" as="button" padding=""  wire:click="edit({{ $table['id'] }})">
                            {{ __('admin.edit') }}
                        </x-ui.button-orange>

                        <x-ui.button-red size="sm" as="button" padding="" wire:click="delete({{ $table['id'] }})">
                            {{ __('admin.delete') }}
                        </x-ui.button-red>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
