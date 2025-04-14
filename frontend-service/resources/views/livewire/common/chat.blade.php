@php use Carbon\Carbon; @endphp

<div>
    <style>
        html {
            scroll-behavior: auto !important;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="container-fluid d-flex flex-column" style="height: 100vh;">
        <div class="row flex-grow-1">
            <div class="col-12 d-flex flex-column position-relative">
                <div class="chat-header sticky-top bg-white py-2 px-3 border-bottom mb-3 shadow-sm" style="top: 65px; z-index: 1000;">
                    <h5 class="mb-0 text-center fw-bold text-secondary">
                        @if ($isAdmin)
                            @lang('common.chat_with_user', ['name' => $userName, 'surname' => $userSurname])
                        @else
                            @lang('common.chat_with_support')
                        @endif
                    </h5>
                </div>
                <div
                    x-data="chatLoader()"
                    x-init="observeSentinel()"
                    x-on:messages-updated.window="handleMessagesUpdated()"
                    x-on:scroll-to-bottom.window="scrollToBottom()"
                    class="chat-box bg-light p-3"
                    style="min-height: 100%;"
                    x-ref="chatBox"
                >


                @if($pagination['current_page'] < $pagination['last_page'])
                        <div x-ref="sentinel" style="height: 1px;"></div>
                    @endif

                    <div x-show="loadingMore" x-cloak class="text-center py-2">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>

                    <div class="messages-list pb-5 mt-5" x-ref="messageList">
                        @foreach ($messages as $message)
                            <div class="message-item mb-3" wire:key="message-{{ $message['id'] }}-{{ $loop->index }}">
                                @if (
                                    $loop->first ||
                                    Carbon::parse($message['created_at'])->timezone($timezone)->toDateString()
                                        !== Carbon::parse($messages[$loop->index - 1]['created_at'])->timezone($timezone)->toDateString()
                                )
                                    <div class="sticky-top bg-light text-center text-muted mt-3 mb-2" style="z-index: 1;">
                                        <small class="text-uppercase">
                                            {{ Carbon::parse($message['created_at'])->timezone($timezone)->format('d.m.Y') }}
                                        </small>
                                    </div>
                                @endif

                                @php
                                    $isSelf = $isAdmin ? !$message['from_user'] : $message['from_user'];
                                    $align = $isSelf ? 'end text-end' : 'start text-start';
                                    $bgColor = $isSelf ? '#43a047' : '#fff';
                                    $textColor = $isSelf ? '#fff' : '#212529';
                                @endphp

                                <div class="d-flex mb-2 justify-content-{{ $align }}">

                                    <div class="message-content p-3 rounded shadow-sm"
                                         style="max-width: 70%; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                        <div>{{ $message['content'] }}</div>
                                        <div class="text-muted small text-end mt-1">
                                            {{ Carbon::parse($message['created_at'])->timezone($timezone)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="chat-input-wrapper fixed-bottom bg-white border-top shadow-sm px-3 py-2" style="z-index: 10;">
                        <div
                            x-data="chatInput()"
                            class="d-flex align-items-end gap-2 flex-wrap flex-md-nowrap w-100"
                        >
                            <textarea
                                x-ref="textarea"
                                x-model="msg"
                                class="form-control flex-grow-1"
                                placeholder="@lang('common.type_your_message')"
                                rows="1"
                                style="resize: none; min-height: 38px; max-height: 150px; overflow-y: auto;"
                                @keydown.enter.prevent="
                                    if ($event.ctrlKey || $event.metaKey) {
                                        msg += '\n';
                                        $nextTick(() => autoResize());
                                    } else {
                                        send();
                                    }
                                "
                                @input="autoResize"
                            ></textarea>

                            <button
                                class="btn btn-success"
                                type="button"
                                :disabled="msg.trim().length === 0"
                                @click="send"
                            >
                                @lang('common.send')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function chatLoader() {
        return {
            loadingMore: false,
            previousScrollY: 0,
            previousHeight: 0,

            observeSentinel() {
                this.$nextTick(() => {
                    const sentinel = this.$refs.sentinel;
                    if (!sentinel) return;

                    let observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !this.loadingMore) {
                                this.loadingMore = true;
                                this.previousScrollY = window.scrollY;
                                this.previousHeight = document.body.scrollHeight;

                                Livewire.dispatch('load-more');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 1.0 });

                    observer.observe(sentinel);
                });
            },

            scrollToBottom() {
                requestAnimationFrame(() => {
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                });
            },

            handleMessagesUpdated() {
                requestAnimationFrame(() => {
                    const newHeight = document.body.scrollHeight;
                    const delta = newHeight - this.previousHeight;

                    if (delta > 0) {
                        window.scrollTo({ top: this.previousScrollY + delta, behavior: 'auto' });
                    }

                    this.loadingMore = false;
                    this.observeSentinel();
                });
            }
        };
    }

    function chatInput() {
        return {
            msg: '',
            send() {
                if (this.msg.trim() === '') return;

                Livewire.dispatch('send-message', { message: this.msg });
                this.msg = '';
                this.$nextTick(() => this.autoResize());
            },
            autoResize() {
                const el = this.$refs.textarea;
                if (el) {
                    el.style.height = 'auto';
                    el.style.height = el.scrollHeight + 'px';
                }
            }
        };
    }

    document.addEventListener('livewire:initialized', () => {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        Livewire.dispatch('user-timezone', { timezone });

        Echo.private('chat.{{ $chatId }}')
            .listen('MessageSent', (event) => {
                Livewire.dispatch('new-message', { message: event.message });
            });
    });

    window.addEventListener('DOMContentLoaded', () => {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'auto' });
    });
</script>
