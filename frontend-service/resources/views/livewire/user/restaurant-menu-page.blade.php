<div class="container py-5">

    @include('components.layouts.partials.user-header')


    <div class="d-flex justify-content-between align-items-center mb-4"
         style="position: sticky; top: 0; z-index: 1020; padding: 1rem 0;">
        <x-ui.button-green wire:click="goBack">
            ← {{ __('common.back') }}
        </x-ui.button-green>

        <h2 class="mb-0 fw-bold">
            {{ $restaurantName }}
        </h2>
    </div>



    <div class="row g-4">
        <div class="col-md-8 col-lg-9" x-data="scrollSpy()" x-init="observe()">
            <livewire:common.menu-list
                :menu-items="$menuItems"
                :menu-categories="$menuCategories"
                :readonly="true"
            />
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="sticky-top" style="top: 100px;">
                <div class="bg-white rounded-4 border shadow-sm p-3">
                    <h6 class="fw-semibold text-secondary mb-3">
                        <i class="bi bi-list-ul me-2"></i>
                        {{ __('restaurant.menu_categories') }}
                    </h6>

                    <livewire:common.menu-category-scroller :menu-categories="$menuCategories" />
                </div>
            </div>
        </div>
    </div>
</div>
