@props(['model'])

<div
    class="d-flex gap-1 align-items-center"
    x-data="{
        rating: @entangle($attributes->wire('model')),
    }"
>
    <template x-for="i in [1, 2, 3, 4, 5]" :key="i">
        <i
            :class="i <= rating ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted'"
            class="fs-4 cursor-pointer"
            role="button"
            @click="rating = i"
        ></i>
    </template>
</div>
