<?php

namespace App\Enums;

enum BookingErrorCode: int
{
    case ValidationFailed = 1;
    case UnknownError = 2;
    case Unauthorized = 3;
    case PastBookingNotAllowed = 4;
    case AlreadyHasActiveBooking = 5;
    case BookingCrossesMultipleDays = 6;
    case RestaurantClosedOnThatDay = 7;
    case BookingOutOfWorkingHours = 8;
    case NotEnoughTablesAvailable = 9;
    case MaxPlacesExceeded = 10;
}
