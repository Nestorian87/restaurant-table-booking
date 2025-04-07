<?php

namespace App\Livewire\Admin\Restaurants\Tabs;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\RestaurantAdminRepository;
use Livewire\Attributes\On;

class BookingsTab extends BaseAdminComponent
{
    public int $restaurantId;

    public array $bookings = [];
    public int $page = 1;
    public bool $hasMorePages = true;

    public string $status = '';
    public string $sortBy = 'start_time';
    public string $sortDir = 'desc';
    private RestaurantAdminRepository $repository;

    public function boot(RestaurantAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount($restaurantId)
    {
        $this->restaurantId = $restaurantId;
        $this->loadBookings();
    }

    public function updated($name)
    {
        if (in_array($name, ['status', 'sortBy', 'sortDir'])) {
            $this->loadBookings();
        }
    }

    public function loadBookings()
    {
        $this->page = 1;
        $this->loadMore(true);
    }

    public function loadMore(bool $reset = false)
    {
        $result = $this->repository->getRestaurantBookings(
            $this->restaurantId,
            $this->status,
            $this->sortBy,
            $this->sortDir,
            $this->page
        );

        $this->handleApiResult($result,
            onSuccess: function ($data) use ($reset) {
                if ($reset) {
                    $this->bookings = $data['data'] ?? [];
                } else {
                    $this->bookings = array_merge($this->bookings, $data['data'] ?? []);
                }
                $this->hasMorePages = $data['meta']['current_page'] < $data['meta']['last_page'];
                $this->page++;
            },
            onFailure: function () {
                $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => __('common.something_went_wrong'),
                ]);
            }
        );
    }

    #[On('booking:cancel')]
    public function cancelBooking(int $bookingId)
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $bookingId,
            'title' => __('admin.booking_cancel_confirmation'),
            'key' => 'booking',
            'type' => 'cancellation'
        ]);
    }

    #[On('booking:cancel-confirmed')]
    public function bookingCancelConfirmed(int $id)
    {
        $result = $this->repository->cancelBooking($id);
        $this->handleApiResult($result,
            onSuccess: function ($data) {
                $this->loadBookings();
            },
            onFailure: function () {
                $this->dispatch('swal:show', [
                    'type' => 'error',
                    'title' => __('common.error'),
                    'text' => __('common.something_went_wrong'),
                ]);
            }
        );
    }

    public function render()
    {
        return view('livewire.admin.restaurants.tabs.bookings-tab');
    }
}
