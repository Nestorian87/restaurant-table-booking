<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="{{ route('admin.dashboard') }}">
            Admin Panel
        </a>

        <div class="d-flex ms-auto">
            <button class="btn btn-outline-danger" id="admin-logout-btn">@lang('auth.logout')</button>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('admin-logout-btn').addEventListener('click', function () {
            localStorage.removeItem('admin_token');
            window.location.href = "{{ route('admin.login') }}";
        });
    });
</script>
