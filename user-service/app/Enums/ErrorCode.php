<?php

namespace App\Enums;

enum ErrorCode: int
{
    case ValidationFailed = 1;
    case UserAlreadyExists = 2;
    case UnknownError = 3;
    case Unauthorized = 4;
}
