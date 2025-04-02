<?php

namespace App\Enums;

enum RestaurantErrorCode: int
{
    case ValidationFailed = 1;
    case UnknownError = 2;
    case Unauthorized = 3;
}
