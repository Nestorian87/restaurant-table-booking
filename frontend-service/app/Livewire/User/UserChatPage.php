<?php

namespace App\Livewire\User;

use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\ChatUserRepository;
use App\Repositories\User\ProfileUserRepository;
use Livewire\Attributes\On;
use Livewire\Component;

class UserChatPage extends BaseUserComponent
{
    public int $page = 1;
    public array $messages = [];
    public array $pagination = [];
    public string $timezone = 'UTC';
    public int $chatId = -1;

    private ChatUserRepository $chatUserRepository;
    private ProfileUserRepository $profileUserRepository;

    public function boot(ChatUserRepository $chatUserRepository, ProfileUserRepository $profileUserRepository)
    {
        $this->profileUserRepository = $profileUserRepository;
        $this->chatUserRepository = $chatUserRepository;
    }

    public function mount()
    {
        $profileResult = $this->profileUserRepository->getProfile();
        $this->handleApiResult($profileResult, onSuccess: function ($data) {
            $this->chatId = $data['id'];
            $this->loadMessages(true);
        }, onFailure: function () {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
        });
    }

    #[On('user-timezone')]
    public function handleUserTimezone(string $timezone)
    {
        $this->timezone = $timezone;
    }

    #[On('new-message')]
    public function appendMessage(array $message)
    {
        $this->messages[] = $message;
        $this->dispatch('scroll-to-bottom');

        if (!$message['from_user']) {
            $result = $this->chatUserRepository->markAsRead();
            $this->handleApiResult(
                $result,
                onFailure: function () {
                    $this->dispatch('swal:show', [
                        'type' => 'error',
                        'title' => __('common.error'),
                        'text' => __('common.something_went_wrong'),
                    ]);
                }
            );
        }
    }

    public function updatedMessages()
    {
        $this->dispatch('messages-updated');
    }

    public function loadMessages(bool $initial = false)
    {
        $result = $this->chatUserRepository->getMessages($this->page);

        $this->handleApiResult($result, onSuccess: function ($data) use ($initial) {
            $fetched = array_reverse($data['data']);
            $this->pagination = $data['meta'];
            $this->page = $this->pagination['current_page'];

            if ($initial) {
                $this->messages = $fetched;
            } else {
                $this->messages = array_merge($fetched, $this->messages);
                $this->dispatch('messages-updated');
            }
        });
    }

    #[On('load-more')]
    public function loadMore()
    {
        if ($this->pagination['current_page'] < $this->pagination['last_page']) {
            $this->page++;
            $this->loadMessages();
        }
    }

    #[On('send-message')]
    public function handleSendMessage(string $message)
    {
        $result = $this->chatUserRepository->sendMessage($message);
        $this->handleApiResult($result, onFailure: function () {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
        });
    }

    public function render()
    {
        return view('livewire.user.chat-page');
    }
}
