<div class="row g-4 mb-4">
    <x-admin.stat-card color="success" :title="__('admin.total_users')" :value="$usersCount" />
    <x-admin.stat-card color="orange" :title="__('admin.active_bookings')" :value="$activeBookingsCount" />
    <x-admin.stat-card color="secondary" :title="__('admin.total_reviews')" :value="$reviewsCount" />
</div>
