<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\BookingUserRepository;
use Livewire\Attributes\On;
use Livewire\Component;

class BookingHistory extends BaseUserComponent
{
    public array $bookings = [];
    public int $reviewingId = 0;
    public array $review = [
        'rating_kitchen' => 5,
        'rating_interior' => 5,
        'rating_service' => 5,
        'rating_atmosphere' => 5,
        'text' => '',
    ];
    public string $timezone = 'UTC';

    protected BookingUserRepository $repository;

    public function boot(BookingUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount(): void
    {
        $this->loadBookings();
    }

    public function loadBookings(): void
    {
        $result = $this->repository->getBookings();

        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->bookings = $data;
        });
    }

    public function cancelBooking($bookingId): void
    {
        $this->dispatch('swal:confirm-delete', [
            'id' => $bookingId,
            'title' => __('bookings.confirm_cancel'),
            'key' => 'booking',
            'type' => 'cancellation'
        ]);
    }

    #[On('booking:cancel-confirmed')]
    public function bookingCancelConfirmed(int $id)
    {
        $result = $this->repository->cancelBooking($id);

        $this->handleApiResult($result, onSuccess: function () {
            $this->loadBookings();
            $this->dispatch('swal:show', [
                'type' => 'success',
                'title' => __('bookings.cancelled'),
            ]);
        });
    }

    public function startReview($bookingId): void
    {
        $this->reviewingId = $bookingId;
    }

    public function submitReview(): void
    {
        $this->validate([
            'review.rating_kitchen' => 'required|integer|min:1|max:5',
            'review.rating_interior' => 'required|integer|min:1|max:5',
            'review.rating_service' => 'required|integer|min:1|max:5',
            'review.rating_atmosphere' => 'required|integer|min:1|max:5',
            'review.text' => 'string|max:1000',
        ]);

        $payload = $this->review + ['timezone' => $this->timezone];

        $result = $this->repository->leaveReview($this->reviewingId, $payload);


        $this->handleApiResult($result, onSuccess: function () {
            $this->loadBookings();
            $this->reviewingId = 0;
            $this->dispatch('swal:show', [
                'type' => 'success',
                'title' => __('bookings.review_success'),
            ]);
        });
    }

    #[On('user-timezone')]
    public function setUserTimezone($timezone): void
    {
        if (!empty($timezone)) {
            $this->timezone = $timezone;
        }
    }

    public function render()
    {
        return view('livewire.user.booking-history');
    }
}
