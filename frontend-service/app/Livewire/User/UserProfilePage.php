<?php

namespace App\Livewire\User;

use App\Dto\ApiResult;
use App\Enums\UserErrorCode;
use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\ProfileUserRepository;
use Illuminate\Support\Facades\Cookie;

class UserProfilePage extends BaseUserComponent
{
    public string $name = '';
    public string $surname = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    private ProfileUserRepository $repository;

    public function boot(ProfileUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        $result = $this->repository->getProfile();
        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->name = $data['name'];
            $this->surname = $data['surname'];
            $this->email = $data['email'];
        }, onFailure: function ($result) {
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => __('common.something_went_wrong'),
            ]);
        });
    }

    public function save()
    {
        $password = $this->password ?: null;

        if ($password && $password !== $this->password_confirmation) {
            $this->dispatch('swal:show', [
                'type' => 'warning',
                'title' => __('common.error'),
                'text' => __('auth.passwords_do_not_match'),
            ]);
            return;
        }

        $result = $this->repository->updateProfile($this->name, $this->surname, $this->email, $password);
        $this->handleApiResult($result, onSuccess: function ($data) {
            $this->dispatch('swal:show', [
                'type' => 'success',
                'title' => __('user.profile_updated')
            ]);
        }, onFailure: function (ApiResult $result) {
            $error = UserErrorCode::tryFrom($result->errorCode ?? null);

            $text = match ($error) {
                UserErrorCode::ValidationFailed => __('common.validation_error'),
                UserErrorCode::UserAlreadyExists => __('auth.email_already_used'),
                default => __('common.something_went_wrong'),
            };

            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => $text,
            ]);
        });
    }
    public function goBack()
    {
        $this->dispatch('spa:navigate', [
            'url' => route('user.dashboard')
        ]);
    }

    public function render()
    {
        return view('livewire.user.user-profile-page');
    }
}

