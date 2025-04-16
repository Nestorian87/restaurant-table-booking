@php use Illuminate\Support\Facades\Cookie; @endphp
    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('common.app_name')</title>
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <style>
        body {
            background: linear-gradient(to right, #e8f5e9, #fffde7);
            min-height: 100vh;
            padding-top: 60px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            background-color: #43a047;
            border-color: #43a047;
        }

        .btn-success:hover {
            background-color: #388e3c;
            border-color: #388e3c;
        }

        .text-success {
            color: #43a047 !important;
        }

        .bg-success {
            background-color: #43a047 !important;
            color: white;
        }

        .bg-success-subtle {
            background-color: #78cf7c !important;
            color: white;
        }

        .btn-orange {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
        }

        .btn-orange:hover {
            background-color: #fb8c00;
            border-color: #fb8c00;
            color: white;
        }

        .bg-orange {
            background-color: #ff9800 !important;
            color: white;
        }

        .navbar {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .list-group-item {
            transition: all 0.3s ease-in-out;
            border-color: transparent;
        }

        .list-group-item.active {
            background-color: #43a047;
            border-color: #43a047;
            color: white !important;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(67, 160, 71, 0.2);
        }

        .swiper-button-prev,
        .swiper-button-next {
            color: #fb8c00;
        !important;
            transition-duration: 0.3s;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            color: #dc8c13;
        !important;
        }

        .btn-close.white-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
</head>
<body>
<div id="new-message-alerts" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;"></div>
<div class="container-fluid">
    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.2.0/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@livewireScripts
<script>
    window.addEventListener('swal:show', e => {
        Swal.fire({
            icon: e.detail[0].type,
            title: e.detail[0].title,
            text: e.detail[0].text,
            timer: e.detail[0].timer,
        });
    });

    window.addEventListener('swal:confirm-delete', e => {
        const isCancellation = e.detail[0].type === 'cancellation'
        Swal.fire({
            title: e.detail[0].title,
            text: e.detail[0].name,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: isCancellation ? '@lang('common.yes')' : '@lang('admin.delete')',
            cancelButtonText: '@lang('common.cancel')'
        }).then(result => {
            if (result.isConfirmed) {
                console.log({id: e.detail[0].id})
                Livewire.dispatch(`${e.detail[0].key}:${isCancellation ? 'cancel' : 'delete'}-confirmed`, {id: e.detail[0].id});
            }
        });
    });

    function getCookie(name) {
        let nameEq = name + "=";
        let cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            let c = cookies[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEq) === 0) {
                return c.substring(nameEq.length, c.length);
            }
        }
        return null;
    }

    function getUserIdFromToken(token) {
        try {
            const payload = token.split('.')[1];
            const decoded = JSON.parse(atob(payload));
            return decoded.sub || null;
        } catch (e) {
            console.error('Invalid token format');
            return null;
        }
    }

    function showNewMessageAlert(messageText, chatId, isAdmin = false, senderName = '', senderSurname = '') {
        const currentPath = window.location.pathname;

        if (
            (!isAdmin && currentPath === '{{ route('user.chat', [], false) }}') ||
            (isAdmin && currentPath === '{{ route('admin.chat.user', ['chatId' => '__CHAT_ID__'], false) }}'.replace('__CHAT_ID__', chatId))
        ) {
            return;
        }

        const alertId = `alert-${Date.now()}`;
        const container = document.getElementById('new-message-alerts');

        const href = isAdmin
            ? `{{ route('admin.chat.user', ['chatId' => '__CHAT_ID__']) }}`.replace('__CHAT_ID__', chatId)
            : `{{ route('user.chat') }}`;

        const senderBlock = isAdmin
            ? `<div class="fw-semibold small text-white mb-1">${senderName} ${senderSurname}</div>`
            : '';

        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
        <a href="${href}" class="text-decoration-none text-reset">
            <div id="${alertId}" class="alert bg-orange alert-dismissible fade show shadow mb-2" role="alert" style="cursor: pointer;">
                <strong>{{ __('common.new_message') }}</strong>
                ${senderBlock}
                <div class="me-auto text-truncate" style="max-width: 300px;">${messageText}</div>
                <button type="button" class="btn-close white-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </a>
    `;
        wrapper.querySelector('.btn-close')?.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
        });

        const alertEl = wrapper.firstElementChild;
        container.appendChild(alertEl);

        const alerts = container.querySelectorAll('.alert');
        if (alerts.length > 5) {
            alerts[0].closest('a')?.remove();
        }

        const bsAlert = new bootstrap.Alert(alertEl.querySelector('.alert'));
        setTimeout(() => {
            bsAlert.close();
        }, 20000);
    }

    function updateUnreadCountBadge(unreadCount) {
        const badge = document.getElementById('chat-unread-badge');
        if (!badge) {
            return;
        }

        if (unreadCount > 0) {
            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            badge.classList.remove('d-none');
        } else {
            badge.classList.add('d-none');
        }
    }

    let lastUnreadCount = 0;
    let token;
    const isAdmin = window.location.pathname.includes("admin");
    if (isAdmin) {
        token = '{{ Cookie::get('admin_token')  }}';
    } else {
        token = '{{ Cookie::get('user_token')  }}';
    }

    if (token) {
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: 'local',
            cluster: 'mt1',
            wsHost: '{{ env('WEBSOCKETS_HOST') }}',
            wsPort: 6001,
            forceTLS: true,
            encrypted: true,
            enabledTransports: ['ws', 'wss'],
            wsPath: '/ws',
            authEndpoint: '{{ env('WEBSOCKETS_BASE_URL') }}/broadcasting/auth',
            auth: {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            }
        });

        if (isAdmin) {
            Echo.private('admin.chats')
                .listen('NewMessageForAdmin', (event) => {
                    const message = event.message;
                    if (message.from_user) {
                        showNewMessageAlert(message.content, message.user.id, true, message.user.name, message.user.surname);
                        updateUnreadCountBadge(++lastUnreadCount)
                    }
                });
        } else {
            const userId = getUserIdFromToken(token);
            if (userId) {
                Echo.private(`chat.${userId}`)
                    .listen('MessageSent', (event) => {
                        const message = event.message;
                        if (!message.from_user) {
                            showNewMessageAlert(message.content, null, false);
                            updateUnreadCountBadge(++lastUnreadCount);
                        }
                    });
            }
        }
    }

    window.Echo = window.Echo || {};
    if (typeof window.Echo.socketId !== 'function') {
        window.Echo.socketId = () => null;
    }

    Livewire.on('spa:navigate', (data) => {
        window.location.href = data[0].url;
    });

    Livewire.on('spa:reload', () => {
        window.location.reload();
    });

    Livewire.on('unread-count', ({unreadCount}) => {
        lastUnreadCount = unreadCount;
        updateUnreadCountBadge(unreadCount);
    });
    @stack('scripts')
</script>
</body>
</html>

