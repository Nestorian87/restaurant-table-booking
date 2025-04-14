<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\ChatUserRepository;
use Livewire\Component;
use Livewire\Attributes\On;

class UnreadCountNotifier extends BaseUserComponent
{
    private bool $hasFetched = false;

    private ChatUserRepository $chatUserRepository;

    public function boot(ChatUserRepository $repository)
    {
        $this->chatUserRepository = $repository;
    }

    public function mount()
    {
        if (!$this->hasFetched) {
            $this->hasFetched = true;
            $result = $this->chatUserRepository->getChat();
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
