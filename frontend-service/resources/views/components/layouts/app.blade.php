<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Booking</title>
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e8f5e9, #fffde7);
            min-height: 100vh;
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
    </style>
</head>
<body>
<div class="container py-5">
    <div class="text-end mb-4">
        <div class="btn-group" role="group" aria-label="Language switcher">
            <a href="{{ route('lang.switch', 'en') }}"
               class="btn {{ app()->getLocale() === 'en' ? 'btn-success' : 'btn-outline-success' }}">
                🇬🇧 EN
            </a>
            <a href="{{ route('lang.switch', 'uk') }}"
               class="btn {{ app()->getLocale() === 'uk' ? 'btn-orange' : 'btn-outline-warning' }}">
                🇺🇦 UA
            </a>
        </div>
    </div>

    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('swal:show', e => {
        Swal.fire({
            icon: e.detail[0].type,
            title: e.detail[0].title,
            text: e.detail[0].text,
        });
    });

    window.addEventListener('swal:confirm-delete', e => {
        Swal.fire({
            title: `@lang('admin.confirm_delete')`,
            text: `${e.detail[0].name}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: '@lang('admin.delete')',
            cancelButtonText: '@lang('common.cancel')'
        }).then(result => {
            if (result.isConfirmed) {
                Livewire.dispatch('restaurant:delete-confirmed', { id: e.detail[0].id });
            }
        });
    });
</script>
@livewireScripts
<script>
    Livewire.on('spa:navigate', (data) => {
        window.location.href = data[0].url;
    });
</script>
</body>
</html>

