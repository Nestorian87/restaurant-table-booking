<?php

namespace App\Dto;

readonly class ApiResult
{
    public function __construct(
        public bool       $success,
        public array|null $data = null,
        public int|null   $errorCode = null,
        public ?int $status = null,
    ) {}

    public function isError(): bool
    {
        return !$this->success;
    }
}

