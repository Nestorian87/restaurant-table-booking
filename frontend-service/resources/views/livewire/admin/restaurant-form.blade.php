<div class="container">
    <h2 class="fw-bold mb-4">
        {{ $restaurantId ? __('admin.edit_restaurant') : __('admin.add_restaurant') }}
    </h2>

    <form wire:submit="save">
        <x-ui.input name="name" label="{{ __('admin.name') }}" maxlength="100" model="name" :required="true"/>
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <x-ui.input name="location" label="{{ __('admin.location') }}" maxlength="255" model="location"
                    :required="true"/>
        @error('location')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <x-ui.input
            name="phone"
            label="{{ __('admin.phone') }}"
            type="tel"
            model="phone"
            maxlength="13"
            pattern="\+380[0-9]{9}"
            placeholder="+380XXXXXXXXX"
        />
        @error('phone')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <x-ui.input name="description" label="{{ __('admin.description') }}" maxlength="1000" model="description"
                    type="textarea"/>
        @error('description')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <x-ui.input name="max_booking_places" label="{{ __('admin.max_booking_places') }}" min="1" max="100"
                    model="maxBookingPlaces"
                    required
                    type="number"/>
        @error('max_booking_places')
        <span class="text-danger">{{ $message }}</span>
        @enderror

        <livewire:admin.working-hours-form :existing="$workingHours" wire:model="workingHours"/>

        @if($restaurantId)
            <x-ui.button-red type="button" class="mt-3" wire:click="delete">
                {{ __('common.delete') }}
            </x-ui.button-red>

            <x-ui.button-green type="submit" as="input" class="mt-3">
                {{ __('admin.save') }}
            </x-ui.button-green>
        @else
            <x-ui.button-green type="submit" as="input" class="mt-3">
                {{ __('admin.create') }}
            </x-ui.button-green>
        @endif
    </form>
</div>
