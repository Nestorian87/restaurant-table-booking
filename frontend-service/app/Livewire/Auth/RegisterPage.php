<?php

namespace App\Livewire\Auth;

use App\Enums\UserErrorCode;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class RegisterPage extends Component
{
    public string $name = '';
    public string $surname = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

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

        $response = Http::acceptJson()->post(config('services.api_base_url') . '/users/register', [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation
        ]);

        $data = $response->json();

        if ($response->failed()) {
            $error = UserErrorCode::tryFrom($data['error_code'] ?? null);

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
            return;
        }

        Cookie::queue('user_token', $data['access_token'], 60 * 24);
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
