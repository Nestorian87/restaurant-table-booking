<?php

namespace App\Livewire\Auth;

use App\Dto\ApiResult;
use App\Enums\UserErrorCode;
use App\Livewire\Base\BaseUserComponent;
use App\Repositories\User\AuthUserRepository;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LoginPage extends BaseUserComponent
{
    public string $email = '';
    public string $password = '';

    private AuthUserRepository $repository;

    public function boot(AuthUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        if (Cookie::has('user_token')) {
            $this->dispatch('spa:navigate', [
                'url' => route('user.dashboard')
            ]);
        }
    }

    public function login()
    {
        $result = $this->repository->login($this->email, $this->password);
        $this->handleApiResult(
            $result,
            onSuccess: function ($data) {
                Cookie::queue('user_token', $data['access_token'], 60 * 24);
                $this->dispatch('spa:navigate', [
                    'url' => route('user.dashboard')
                ]);
            }, onFailure: function (ApiResult $result) {
            $error = UserErrorCode::tryFrom($result->errorCode ?? null);

            $text = match ($error) {
                UserErrorCode::ValidationFailed => __('common.validation_error'),
                UserErrorCode::Unauthorized => __('auth.invalid_credentials'),
                default => __('common.something_went_wrong'),
            };
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => $text
            ]);
        }, logoutOnUnauthorized: false
        );

    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
