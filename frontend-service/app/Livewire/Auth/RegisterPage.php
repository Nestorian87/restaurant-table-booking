<?php

namespace App\Livewire\Auth;

use App\Dto\ApiResult;
use App\Enums\UserErrorCode;
use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\AuthUserRepository;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RegisterPage extends BaseUserComponent
{
    public string $name = '';
    public string $surname = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    private AuthUserRepository $repository;

    public function boot(AuthUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register()
    {
        if ($this->password !== $this->password_confirmation) {
            $this->dispatch('swal:show', [
                'type' => 'warning',
                'title' => __('common.error'),
                'text' => __('auth.passwords_do_not_match'),
            ]);
            return;
        }

       if (strlen($this->password) < 6) {
            $this->dispatch('swal:show', [
                'type' => 'warning',
                'title' => __('common.error'),
                'text' => __('auth.password_too_short'),
            ]);
            return;
        }

        $result = $this->repository->register($this->name, $this->surname, $this->email, $this->password);
        $this->handleApiResult($result, onSuccess: function ($data) {
            Cookie::queue('user_token', $data['access_token'], 60 * 24);
            $this->dispatch('spa:navigate', [
                'url' => route('user.dashboard')
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

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
