<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\Admin\ChatAdminRepository;
use App\Repositories\User\ChatUserRepository;
use Livewire\Component;
use Livewire\Attributes\On;

class UnreadCountNotifier extends BaseUserComponent
{
    private bool $hasFetched = false;

    private ChatAdminRepository $chatAdminRepository;

    public function boot(ChatAdminRepository $repository)
    {
        $this->chatAdminRepository = $repository;
    }

    public function mount()
    {
        if (!$this->hasFetched) {
            $this->hasFetched = true;
            $result = $this->chatAdminRepository->getUnreadCount();
            $this->handleApiResult(
                $result,
                onSuccess: function ($data) {
                    $this->dispatch('unread-count', unreadCount: $data['unread_count']);
                },
            );
        }
    }

    public function render()
    {
        return '<div></div>';
    }
}
