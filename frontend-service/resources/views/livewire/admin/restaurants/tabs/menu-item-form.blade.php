<form wire:submit="submit" class="container bg-white p-4 rounded-4 shadow-sm" style="max-width: 800px;">

    {{-- Фото --}}
    <div class="d-flex justify-content-center mb-4">
        <div class="position-relative rounded-4 overflow-hidden shadow-sm" style="width: 220px; height: 160px;">
            @if($photo)
                <img src="{{ $photo->temporaryUrl() }}"
                     alt="Preview"
                     class="w-100 h-100 object-fit-cover"/>
            @elseif(!empty($editing['photo_url']))
                <img src="{{ $editing['photo_url'] }}"
                     alt="Photo"
                     class="w-100 h-100 object-fit-cover"/>
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                    <i class="bi bi-card-image text-muted" style="font-size: 2.5rem;"></i>
                </div>
            @endif

            <label for="photo"
                   class="position-absolute bottom-0 end-0 m-2 btn btn-sm btn-light border shadow-sm rounded-circle"
                   title="{{ __('admin.upload_photo') }}"
                   style="z-index: 10;">
                <i class="bi bi-upload"></i>
            </label>
            <input type="file" id="photo" wire:model="photo" class="d-none"/>
        </div>
    </div>

    {{-- Ряд 1: Назва та Ціна --}}
    <div class="row gx-4 gy-3">
        <div class="col-md-8">
            <x-ui.input
                name="name"
                label="{{ __('admin.name') }}"
                model="name"
                maxlength="255"
                required
            />
        </div>
        <div class="col-md-4">
            <x-ui.input
                name="price"
                label="{{ __('admin.price') }}"
                model="price"
                type="number"
                min="1"
                required
            />
        </div>

        {{-- Ряд 2: Опис --}}
        <div class="col-12">
            <label for="description" class="form-label mb-1">
                {{ __('admin.description') }}
            </label>
            <textarea
                id="description"
                name="description"
                wire:model="description"
                class="form-control rounded-3 px-3 py-2"
                rows="3"
                maxlength="300"
                style="resize: none;"
            ></textarea>
            @error('description')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Ряд 3: Категорія + Обʼєм + Одиниця виміру --}}
        <div class="col-md-6">
            <x-ui.select
                name="menu_category_id"
                label="{{ __('admin.category') }}"
                model="menu_category_id"
                :options="$menuCategories"
                option-value="id"
                option-label="name"
                :required="true"
                :searchable="true"
                :current-value="$menu_category_id"
            />
        </div>

        <div class="col-md-3">
            <x-ui.input
                name="volume"
                label="{{ __('admin.volume') }}"
                model="volume"
                type="number"
                step="0.01"
                min="1"
                required
            />
        </div>

        <div class="col-md-3">
            <x-ui.select
                name="unit"
                label="{{ __('admin.unit') }}"
                model="unit"
                :options="[
                    ['id' => 'g', 'name' => __('units.g')],
                    ['id' => 'ml', 'name' => __('units.ml')],
                    ['id' => 'pcs', 'name' => __('units.pcs')],
                ]"
                :required="true"
                :current-value="$unit"
            />
        </div>
    </div>

    {{-- Кнопка --}}
    <div class="d-flex justify-content-end mt-4">
        <x-ui.button-orange as="button" type="submit" size="lg" class="px-5 py-2 rounded-3 shadow-sm">
            {{ $editId ? __('admin.update') : __('admin.add') }}
        </x-ui.button-orange>
    </div>

</form>
