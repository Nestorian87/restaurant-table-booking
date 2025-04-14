<?php

namespace App\Livewire\Admin;

use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\ChatAdminRepository;
use Livewire\Component;
use Livewire\WithPagination;

class AdminChatsPage extends BaseAdminComponent
{
    use WithPagination;

    public array $chats = [];
    public array $pagination = [];
    public int $page = 1;
    protected array $queryString = ['page'];

    private ChatAdminRepository $repository;

    public function boot(ChatAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $this->loadChats();
    }

    public function loadChats()
    {
        $result = $this->repository->getChats($this->page);

        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->pagination = $data['meta'];
            if ($this->page === 1) {
                $this->chats = $data['data'];
            } else {
                $this->chats = array_merge($this->chats, $data['data']);
            }
        }, onFailure: fn() => $this->dispatch('swal:show', [
            'type' => 'error',
            'title' => __('common.error'),
            'text' => __('common.something_went_wrong'),
        ]));
    }

    public function loadMore()
    {
        if ($this->pagination['current_page'] < $this->pagination['last_page']) {
            $this->page++;
            $this->loadChats();
        }
    }

    public function updateLastMessage($message)
    {
        foreach ($this->chats as &$chat) {
            if ($chat['user']['id'] == $message['user_id']) {
                $chat['last_message'] = $message;
                $chat['messages_count'] += 1;
                break;
            }
        }

        usort($this->chats, function ($a, $b) use ($message) {
            return $a['user']['id'] == $message['user_id'] ? -1 : 1;
        });
    }

    public function render()
    {
        return view('livewire.admin.chats-page');
    }
}
