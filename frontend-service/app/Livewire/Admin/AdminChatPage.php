<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\ChatAdminRepository;
use Livewire\Component;
use Livewire\Attributes\On;

class AdminChatPage extends BaseAdminComponent
{
    public int $chatId;
    public int $page = 1;
    public array $messages = [];
    public array $pagination = [];
    public string $timezone = 'UTC';
    public string $userName = '';
    public string $userSurname = '';

    private ChatAdminRepository $repository;

    public function boot(ChatAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount($chatId)
    {
        $this->chatId = $chatId;
        $this->loadMessages(true);

        $chatResult = $this->repository->getChat($this->chatId);
        $this->handleApiResult($chatResult, onSuccess: function ($data) {
            $this->userName = $data['data']['user']['name'];
            $this->userSurname = $data['data']['user']['surname'];
        }, onFailure: function () {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
        });
    }

    public function updatedMessages()
    {
        $this->dispatch('messages-updated');
    }

    public function loadMessages(bool $initial = false)
    {
        $result = $this->repository->getMessages($this->chatId, $this->page);

        $this->handleApiResult($result, onSuccess: function ($data) use ($initial) {
            $fetched = array_reverse($data['data']);
            $this->pagination = $data['meta'];
            $this->page = $this->pagination['current_page'];

            if ($initial) {
                $this->messages = $fetched;
            } else {
                $this->messages = array_merge($fetched, $this->messages);
                error_log('pagination: ' . json_encode($this->pagination));
                error_log('page: ' . json_encode($this->page));
                $this->dispatch('messages-updated');
            }
        }, onFailure: function () {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
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
        $result = $this->repository->sendMessage($this->chatId, $message);
        $this->handleApiResult($result, onFailure: function () {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
        });
    }

    #[On('new-message')]
    public function handleNewIncomingMessage(array $message)
    {
        $this->messages[] = $message;
        $this->dispatch('scroll-to-bottom');

        if ($message['from_user']) {
            $result = $this->repository->markAsRead($this->chatId);
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

    #[On('user-timezone')]
    public function handleUserTimezone(string $timezone)
    {
        $this->timezone = $timezone;
    }

    public function render()
    {
        return view('livewire.admin.chat-page');
    }
}
