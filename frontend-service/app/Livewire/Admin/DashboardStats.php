<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\StatisticsAdminRepository;
use Livewire\Component;

class DashboardStats extends BaseAdminComponent
{
    public int $usersCount = 0;
    public int $activeBookingsCount = 0;
    public int $reviewsCount = 0;

    private StatisticsAdminRepository $repository;

    public function boot(StatisticsAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }

    public function mount()
    {
        $this->getUsersCount();
        $this->getBookingsStatistics();
    }

    private function getUsersCount() {
        $result = $this->repository->getUsersStatistics();
        $this->handleApiResult($result, onSuccess: fn($data) => $this->usersCount = $data['count']);
    }

    private function getBookingsStatistics() {
        $result = $this->repository->getBookingsStatistics();
        $this->handleApiResult($result, onSuccess: function($data) {
            $this->activeBookingsCount = $data['active_bookings_count'];
            $this->reviewsCount = $data['reviews_count'];
        });
    }
}
