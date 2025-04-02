<?php

namespace App\Livewire\Auth;

use App\Enums\UserErrorCode;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class LoginPage extends Component
{
    public string $email = '';
    public string $password = '';

    public function login()
    {
        Log::info('Login request triggered', [
            'email' => $this->email,
            'timestamp' => now()->toDateTimeString()
        ]);


        $response = Http::acceptJson()->post(config('services.api_base_url') . '/users/login', [
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
            Log::error('data: ' . var_export($data, true));
            $this->dispatch('swal:show', [
                'type' => 'error',
                'title' => __('common.error'),
                'text' => $text
            ]);
            return;
        }
        Cookie::queue('user_token', $data['access_token'], 60 * 24);
    }

    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
