<?php

namespace App\Livewire\Base;

use App\DTO\ApiResult;
use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

abstract class BaseComponent extends Component
{
    abstract protected function getTokenCookieName(): string;
    abstract protected function getLoginRoute(): string;

    protected function handleApiResult(ApiResult $result, ?callable $onSuccess = null, ?callable $onFailure = null)
    {
        if ($result->status === 401) {
            Cookie::queue(Cookie::forget($this->getTokenCookieName()));

            $this->dispatch('spa:navigate', [
                'url' => route($this->getLoginRoute())
            ]);

            return null;
        }

        if ($result->success) {
            return $onSuccess ? $onSuccess($result->data) : null;
        }

        if ($onFailure) {
            return $onFailure($result);
        }

        $this->dispatch('swal:show', [
            'type' => 'error',
            'title' => __('common.error'),
            'text' => $result->message ?? __('common.something_went_wrong'),
        ]);

        return null;
    }
}
