<?php

namespace App\Enums;

enum BookingErrorCode: int
{
    case ValidationFailed = 1;
    case UnknownError = 2;
    case Unauthorized = 3;
}
