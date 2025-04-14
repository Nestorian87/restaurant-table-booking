<?php

namespace App\Livewire\Common;

use Livewire\Component;

class Chat extends Component
{
    public int $chatId;
    public array $messages = [];
    public array $pagination = [];
    public int $page = 1;
    public string $timezone;
    public bool $isAdmin = false;
    public string $userName = '';
    public string $userSurname = '';

    public function render()
    {
        return view('livewire.common.chat');
    }
}
