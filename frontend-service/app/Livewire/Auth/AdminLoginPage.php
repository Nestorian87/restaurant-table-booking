<?php

namespace App\Livewire\Auth;

use App\Dto\ApiResult;
use App\Enums\UserErrorCode;
use App\Livewire\Base\BaseAdminComponent;
use App\Repositories\Admin\AuthAdminRepository;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AdminLoginPage extends BaseAdminComponent
{
    public string $email = '';
    public string $password = '';

    protected AuthAdminRepository $repository;

    public function boot(AuthAdminRepository $repository)
    {
        $this->repository = $repository;
    }

    public function mount()
    {
        if (Cookie::has('admin_token')) {
            $this->dispatch('spa:navigate', [
                'url' => route('admin.dashboard')
            ]);
        }
    }

    public function login()
    {
        $result = $this->repository->login($this->email, $this->password);

        $this->handleApiResult($result, onSuccess: function ($data) {
            Cookie::queue('admin_token', $data['access_token'], 60 * 24);

            $this->dispatch('spa:navigate', [
                'url' => route('admin.dashboard')
            ]);
        }, onFailure: function (ApiResult $result) {
            $error = UserErrorCode::tryFrom($result->errorCode);

            $text = match ($error) {
                UserErrorCode::ValidationFailed => __('common.validation_error'),
                UserErrorCode::Unauthorized => __('auth.invalid_credentials'),
                default => __('common.something_went_wrong'),
            };

            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => $text,
            ]);
        }, logoutOnUnauthorized: false);


    }

    public function render()
    {
        return view('livewire.auth.admin-login-page');
    }
}
