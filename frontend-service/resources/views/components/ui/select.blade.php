@props([
    'name',
    'label' => '',
    'model',
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'required' => false,
    'searchable' => false,
    'currentValue' => null,
])

@php
    $modelValue = $currentValue ?? old($name) ?? ($attributes->wire('model')->value() ?? '');
@endphp

<div
        class="mb-3"
        x-data="
        {
            open: false,
            options: @js($options) ?? [],
            selectedValue: '{{ $modelValue }}' ?? '',
            selectedLabel: '',
            search: '',
            valueKey: '{{ $optionValue }}' ?? 'id',
            labelKey: '{{ $optionLabel }}' ?? 'name',
            required: {{ $required ? 'true' : 'false' }} ?? false,
            searchable: {{ $searchable ? 'true' : 'false' }} ?? false,
            showError: false,

            init() {
                this.selectedLabel = this.getLabelFromValue(this.selectedValue);
                this.search = this.selectedLabel;
                if (this.$refs.hidden) {
                    this.$refs.hidden.value = this.selectedValue;
                }
            },

            getValue(opt) {
                return typeof opt === 'string' ? opt : opt[this.valueKey];
            },
            getLabel(opt) {
                return typeof opt === 'string' ? opt : opt[this.labelKey];
            },
            getLabelFromValue(val) {
                const found = this.options.find(o => this.getValue(o) == val);
                return found ? this.getLabel(found) : '';
            },
            filteredOptions() {
                if (!this.searchable) return this.options;
                return this.options.filter(opt =>
                    this.getLabel(opt).toLowerCase().includes(this.search.toLowerCase())
                );
            },
            select(opt) {
                this.selectedValue = this.getValue(opt);
                this.selectedLabel = this.getLabel(opt);
                this.search = this.selectedLabel;
                this.open = false;
                this.showError = false;
                if (this.$refs.hidden) {
                    this.$refs.hidden.value = this.selectedValue;
                    this.$refs.hidden.dispatchEvent(new Event('input', {bubbles: true}));
                }
                this.$dispatch('input', this.selectedValue);
            },
            validateBeforeSubmit(e) {
                if (!this.searchable) {
                    if (this.required && !this.selectedValue) {
                        this.showError = true;
                        if (this.$refs.hidden) {
                            this.$refs.hidden.value = '';
                            this.$refs.hidden.dispatchEvent(new Event('input', {bubbles: true}));
                        }
                        e.preventDefault();
                    }
                    return;
                }

                const match = this.options.find(o =>
                    this.getLabel(o).toLowerCase() === this.search.toLowerCase()
                );

                if (!match && this.required) {
                    this.showError = true;
                    this.selectedValue = '';
                    if (this.$refs.hidden) {
                        this.$refs.hidden.value = '';
                        this.$refs.hidden.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                    e.preventDefault();
                } else {
                    this.selectedValue = match ? this.getValue(match) : '';
                    this.showError = false;
                    if (this.$refs.hidden) {
                        this.$refs.hidden.value = this.selectedValue;
                        this.$refs.hidden.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                }
            }
        }"
        @submit.window="validateBeforeSubmit($event)">
    @if($label)
        <label class="form-label">
            {{ $label }}
        </label>
    @endif

    <input
            type="hidden"
            name="{{ $name }}"
            x-ref="hidden"
            :value="selectedValue"
            @if($model) wire:model="{{ $model }}" @endif
            @if($required) required @endif
    />

    <div class="position-relative">
        <input
                type="text"
                class="form-control"
                x-ref="searchInput"
                x-model="search"
                :readonly="!searchable"
                :placeholder="'{{ __('common.select_option') }}'"
                :class="{ 'is-invalid': showError }"
                @click="open = true"
                @focus="open = true"
                @click.away="open = false"
                @keydown.escape="open = false"
        />

        <ul
                class="list-group position-absolute w-100 z-3 mt-1 shadow-sm bg-white"
                x-show="open"
                x-cloak
                style="max-height: 250px; overflow-y: auto;"
        >
            <template x-for="opt in filteredOptions()" :key="getValue(opt)">
                <li
                        class="list-group-item d-flex justify-content-between align-items-center list-group-item-action"
                        @click="select(opt)"
                >
                    <span x-text="getLabel(opt)"></span>
                </li>
            </template>
        </ul>
    </div>

    <template x-if="showError">
        <div class="invalid-feedback d-block mt-1">
            {{ __('common.please_select_option') }}
        </div>
    </template>

</div>
