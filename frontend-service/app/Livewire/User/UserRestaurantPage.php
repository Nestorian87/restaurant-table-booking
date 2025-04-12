<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\BookingUserRepository;
use App\Repositories\User\RestaurantUserRepository;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;

class UserRestaurantPage extends BaseUserComponent
{
    public array $restaurant = [];
    public array $reviews = [];
    public array $average = [];
    public ?array $activeBooking = null;
    public string $timezone = 'UTC';


    private RestaurantUserRepository $restaurantRepository;
    private BookingUserRepository $bookingRepository;

    public function boot(RestaurantUserRepository $restaurantRepository, BookingUserRepository $bookingRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function mount(int $restaurantId)
    {
        $restaurantResult = $this->restaurantRepository->getRestaurantById($restaurantId);
        $this->handleApiResult($restaurantResult,
            onSuccess: function ($data) {
                $this->restaurant = $data;

                $result = $this->restaurantRepository->getRestaurantReviews($data['id']);
                $this->handleApiResult($result,
                    onSuccess: function ($data) {
                        $this->reviews = $data['reviews'];
                        $this->average = $data['average'];
                    },
                    onFailure: fn() => $this->dispatch('swal:show', [
                        'type' => 'error',
                        'title' => __('common.error'),
                        'text' => __('common.something_went_wrong'),
                    ])
                );
            },
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );

        $activeBookingResult = $this->bookingRepository->getActiveRestaurantBooking($restaurantId);
        $this->handleApiResult($activeBookingResult,
            onSuccess: function ($data) {
                $this->activeBooking = $data['active_booking'];
            },
            onFailure: fn() => $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ])
        );
    }

    public function toggleReaction(int $reviewId, string $reaction)
    {
        $current = collect($this->reviews)->firstWhere('id', $reviewId);
        $currentReaction = $current['user_reaction'] ?? null;

        $newReaction = $currentReaction === $reaction ? null : $reaction;

        $response = $this->restaurantRepository->reactToReview($reviewId, $newReaction);

        $this->handleApiResult($response, onSuccess: function ($data) use ($reviewId, $newReaction) {
            foreach ($this->reviews as &$review) {
                if ($review['id'] === $reviewId) {
                    $review['likes'] = $data['likes'];
                    $review['dislikes'] = $data['dislikes'];
                    $review['user_reaction'] = $newReaction;
                    break;
                }
            }
        }, onFailure: fn() => $this->dispatch('swal:show', [
            'type' => 'error',
            'title' => __('common.error'),
            'text' => __('common.something_went_wrong'),
        ]));
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
        return view('livewire.user.restaurant-page');
    }
}

