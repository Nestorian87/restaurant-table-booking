<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Booking</title>
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

    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.AppConfig = {
        apiUrl: @json($apiBaseUrl)
    };
</script>
@yield('scripts')
</body>
</html>
