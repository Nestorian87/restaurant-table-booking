<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Booking</title>
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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
            color: #fb8c00; !important;
            transition-duration: 0.3s;
        }

        .swiper-button-prev:hover,
        .swiper-button-next:hover {
            color: #dc8c13; !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    {{ $slot }}
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
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
</script>
@livewireScripts
<script>
    Livewire.on('spa:navigate', (data) => {
        window.location.href = data[0].url;
    });
</script>
@stack('scripts')
</body>
</html>

