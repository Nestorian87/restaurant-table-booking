<div class="row g-4 mb-4">
    <x-admin.stat-card color="success" :title="__('admin.total_users')" :value="$users" />
    <x-admin.stat-card color="orange" :title="__('admin.new_reservations')" :value="$reservations" />
    <x-admin.stat-card color="secondary" :title="__('admin.pending_reviews')" :value="$reviews" />
</div>
