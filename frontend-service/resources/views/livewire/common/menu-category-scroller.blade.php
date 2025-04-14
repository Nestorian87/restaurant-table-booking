<div class="list-group sticky-top" style="top: 80px;" x-data
     x-init="$watch('$store.menuScroll.activeCategoryId', val => active = val)">
    @foreach($menuCategories as $cat)
        <div class="list-group-item d-flex justify-content-between align-items-center"
             :class="{ 'active': $store.menuScroll.activeCategoryId === {{ $cat['id'] }} }"
        >
            <button
                type="button"
                class="btn btn-sm border-0 p-0 text-start flex-grow-1 text-truncate bg-transparent"
                :class="$store.menuScroll.activeCategoryId === {{ $cat['id'] }} ? 'text-white' : 'text-body'"
                @click="$store.menuScroll.scrollToCategory({{ $cat['id'] }})"
            >
                {{ $cat['name'] }}
            </button>

            @if($adminMode)
                <div class="ms-2 d-flex gap-1">
                    <button
                        class="btn btn-sm btn-outline-primary py-0 px-1"
                        title="{{ __('admin.edit') }}"
                        wire:click="editCategory({{ $cat['id'] }}, '{{ addslashes($cat['name']) }}')"
                    >
                        <i class="bi bi-pencil-fill"></i>
                    </button>

                    <button
                        class="btn btn-sm btn-outline-danger py-0 px-1"
                        title="{{ __('admin.delete') }}"
                        wire:click="confirmDeleteCategory({{ $cat['id'] }})"
                    >
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            @endif
        </div>
    @endforeach
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('menuScroll', {
            activeCategoryId: null,
            isScrollingTo: false,
            _scrollEndTimeout: null,

            scrollToCategory(id) {
                this.isScrollingTo = true;
                const el = document.getElementById('category-' + id);
                if (el) el.scrollIntoView({behavior: 'smooth', block: 'start'});

                if (this._scrollEndTimeout) clearTimeout(this._scrollEndTimeout);

                this._scrollEndTimeout = setTimeout(() => {
                    this.isScrollingTo = false;
                }, 300);
            }
        });

        Alpine.data('scrollSpy', () => ({
            observe() {
                const container = document.querySelector('[data-menu-scroll-container]') || window;
                const blocks = Array.from(document.querySelectorAll('[data-category-block]'));
                const lastCategoryId = blocks.length ? parseInt(blocks.at(-1).id.replace('category-', '')) : null;

                const getScrollPosition = () => {
                    return container === window
                        ? window.scrollY + window.innerHeight
                        : container.scrollTop + container.clientHeight;
                };

                const getScrollHeight = () => {
                    return container === window
                        ? document.body.scrollHeight
                        : container.scrollHeight;
                };

                const onScroll = () => {
                    if (Alpine.store('menuScroll').isScrollingTo) return;

                    const buffer = 100;
                    let closest = null;
                    let minOffset = Infinity;

                    const scrollPos = getScrollPosition();
                    const maxScroll = getScrollHeight();

                    const isAtBottom = Math.abs(scrollPos - maxScroll) < 5;

                    if (isAtBottom && lastCategoryId !== null) {
                        Alpine.store('menuScroll').activeCategoryId = lastCategoryId;
                        return;
                    }

                    blocks.forEach(block => {
                        const rect = block.getBoundingClientRect();
                        const offset = Math.abs(rect.top - buffer);

                        if (rect.top <= buffer && offset < minOffset) {
                            closest = block;
                            minOffset = offset;
                        }
                    });

                    if (closest) {
                        const id = parseInt(closest.id.replace('category-', ''));
                        Alpine.store('menuScroll').activeCategoryId = id;
                    }
                };

                let timeout = null;
                container.addEventListener('scroll', () => {
                    if (timeout) cancelAnimationFrame(timeout);
                    timeout = requestAnimationFrame(onScroll);
                });

                onScroll();
            }
        }));
    });
</script>
