<div>
    <style>
        .card {
            transition: background-color 0.5s ease;
        }
        .bg-flash {
            background-color: #e6ffe6 !important;
        }
    </style>
    @include('components.layouts.partials.admin-header')

    @php use Carbon\Carbon; @endphp

    <div x-data="adminChatList()">
        <div class="chat-list">
            @foreach ($chats as $chat)
                <a href="{{ route('admin.chat.user', ['chatId' => $chat['user']['id']]) }}"
                   class="text-decoration-none text-reset chat-entry"
                   id="chat-{{ $chat['user']['id'] }}"
                   data-chat-id="{{ $chat['user']['id'] }}">
                    <div class="card mb-3 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">
                                        {{ $chat['user']['name'] }} {{ $chat['user']['surname'] }}
                                    </h6>
                                    @if (!empty($chat['unread_count']) && $chat['unread_count'] > 0)
                                        <span class="badge bg-orange rounded-pill unread-count">
                                            {{ $chat['unread_count'] }}
                                        </span>
                                    @endif
                                </div>

                                <p class="mb-1 text-muted text-truncate last-message"
                                   style="max-width: 400px;">
                                    {{ $chat['last_message']['content'] }}
                                </p>

                                <small class="text-muted last-message-time">
                                    {{ Carbon::parse($chat['last_message']['created_at'])->format('d.m.y H:i') }}
                                </small>
                            </div>

                            <div class="ms-3 text-end">
                                <span class="badge bg-secondary">{{ __('common.messages') }}: {{ $chat['messages_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($pagination['current_page'] < $pagination['last_page'])
            <div x-init="observe($el)" class="text-center py-4 w-100">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
        @endif
    </div>
</div>

<script>
    function adminChatList() {
        return {
            observe(el) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $wire.loadMore();
                        }
                    });
                }, { threshold: 1.0 });
                observer.observe(el);
            }
        }
    }

    document.addEventListener('livewire:initialized', () => {
        Echo.private('admin.chats')
            .listen('NewMessageForAdmin', (event) => {
                const message = event.message;
                const chatId = message.user_id;
                const container = document.querySelector('.chat-list');
                let chatEl = document.getElementById(`chat-${chatId}`);

                const formattedTime = new Date(message.created_at).toLocaleString('uk-UA', {
                    day: '2-digit',
                    month: '2-digit',
                    year: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                });

                if (chatEl) {
                    chatEl.querySelector('.last-message').innerText = message.content;
                    chatEl.querySelector('.last-message-time').innerText = formattedTime;

                    const badge = chatEl.querySelector('.unread-count');
                    if (badge) {
                        console.log('existing badge', badge);
                        badge.innerText = parseInt(badge.innerText) + 1;
                    } else {
                        console.log('new badge');
                        const newBadge = document.createElement('span');
                        newBadge.className = 'badge bg-orange rounded-pill unread-count';
                        newBadge.innerText = 1;
                        chatEl.querySelector('.fw-bold').after(newBadge);
                    }

                    const card = chatEl.querySelector('.card');
                    card.classList.add('bg-flash');
                    setTimeout(() => card.classList.remove('bg-flash'), 1000);

                    container.prepend(chatEl);
                } else {
                    // Create a new chat entry
                    const newChat = document.createElement('a');
                    newChat.href = `{{ route('admin.chat.user', ['chatId' => '__ID__']) }}`.replace('__ID__', chatId);
                    newChat.className = 'text-decoration-none text-reset chat-entry';
                    newChat.id = `chat-${chatId}`;
                    newChat.dataset.chatId = chatId;

                    newChat.innerHTML = `
                                <div class="card mb-3 border-0 shadow-sm rounded-4 bg-flash">
                                    <div class="card-body d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h6 class="mb-0 fw-bold">
                                                    ${message.user.name} ${message.user.surname}
                                                </h6>
                                                <span class="badge bg-orange rounded-pill unread-count">1</span>
                                            </div>
                                            <p class="mb-1 text-muted text-truncate last-message" style="max-width: 400px;">
                                                ${message.content}
                                            </p>
                                            <small class="text-muted last-message-time">${formattedTime}</small>
                                        </div>
                                        <div class="ms-3 text-end">
                                            <span class="badge bg-secondary">{{ __('common.messages') }}: 1</span>
                                        </div>
                                    </div>
                                </div>
                            `;

                    container.prepend(newChat);

                    setTimeout(() => {
                        newChat.querySelector('.card').classList.remove('bg-flash');
                    }, 1000);
                }
            });
    });
</script>

