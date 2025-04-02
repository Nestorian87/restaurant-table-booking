<?php

namespace App\Livewire\Auth;

use App\Enums\UserErrorCode;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class AdminLoginPage extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        $response = Http::acceptJson()->post(config('services.api_base_url') . '/users/admin/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $data = $response->json();

        if ($response->failed()) {
            $error = UserErrorCode::tryFrom($data['error_code'] ?? null);

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
            return;
        }

        Cookie::queue('admin_token', $data['access_token'], 60 * 24);

        $this->dispatch('spa:navigate', [
            'url' => route('admin.dashboard')
        ]);
    }

    public function render()
    {
        return view('livewire.auth.admin-login-page');
    }
}
