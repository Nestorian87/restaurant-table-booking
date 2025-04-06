<ul class="list-group">
    @foreach($tableTypes as $table)
        <li class="list-group-item d-flex justify-content-between align-items-center {{ $editId == $table['id'] ? 'bg-secondary-subtle' : '' }}">
            <span>{{ $table['tables_count'] }} {{ trans_choice('admin.tables_word', $table['tables_count']) }} × {{ $table['places_count'] }} {{  trans_choice('admin.places_word', $table['places_count']) }}</span>
            <div class="d-flex gap-2">
                <x-ui.button-orange size="sm" as="button" padding=""   wire:click="edit({{json_encode($table)}})">
                    {{ __('admin.edit') }}
                </x-ui.button-orange>

                <x-ui.button-red size="sm" as="button" padding="" wire:click="delete({{ $table['id'] }})">
                    {{ __('admin.delete') }}
                </x-ui.button-red>
            </div>
        </li>
    @endforeach
</ul>
