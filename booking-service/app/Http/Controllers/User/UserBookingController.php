<?php

namespace App\Http\Controllers\User;

use App\Enums\BookingErrorCode;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use App\Models\TableType;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserBookingController extends Controller
{
    public function index(Request $request)
    {

        return Booking::with(['tableTypes', 'review', 'restaurant'])
            ->where('user_id', $request->attributes->get('user_id'))
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'table_types' => 'required|array',
            'table_types.*.id' => 'required|integer|exists:table_types,id',
            'table_types.*.count' => 'required|integer|min:1',
            'timezone' => 'required|string',
        ]);

        $start = Carbon::parse($data['start_time'], $data['timezone']);
        $end = Carbon::parse($data['end_time'], $data['timezone']);

        if ($start->isPast()) {
            return response()->json([
                'error_code' => BookingErrorCode::PastBookingNotAllowed->value,
                'message' => 'Cannot create booking in the past.',
            ], 422);
        }

        $userId = $request->attributes->get('user_id');
        $activeExists = Booking::where('user_id', $userId)
            ->where('restaurant_id', $data['restaurant_id'])
            ->where('end_time', '>', Carbon::now($data['timezone']))
            ->where('status', 'confirmed')
            ->exists();

        if ($activeExists) {
            return response()->json([
                'error_code' => BookingErrorCode::AlreadyHasActiveBooking->value,
                'message' => 'You already have an active booking for this restaurant.',
            ], 422);
        }

        if ($start->isSameDay($end) === false) {
            return response()->json([
                'error_code' => BookingErrorCode::BookingCrossesMultipleDays->value,
                'message' => 'Booking must be within a single day.',
            ], 422);
        }

        $weekday = ($start->dayOfWeekIso + 6) % 7;
        $workingHour = WorkingHour::where('restaurant_id', $data['restaurant_id'])
            ->where('day', $weekday)
            ->first();

        if (!$workingHour) {
            return response()->json([
                'error_code' => BookingErrorCode::RestaurantClosedOnThatDay->value,
                'message' => 'The restaurant is closed on this day.',
            ], 422);
        }

        $startTimeStr = $start->format('H:i:s');
        $endTimeStr = $end->format('H:i:s');

        if ($startTimeStr < $workingHour->open_time || $endTimeStr > $workingHour->close_time) {
            return response()->json([
                'error_code' => BookingErrorCode::BookingOutOfWorkingHours->value,
                'message' => 'Booking is outside of restaurant working hours.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $totalPlaces = 0;

            foreach ($data['table_types'] as $typeRequest) {
                $typeId = $typeRequest['id'];
                $requestedCount = $typeRequest['count'];

                $existingCount = DB::table('booking_table_types')
                    ->join('bookings', 'booking_table_types.booking_id', '=', 'bookings.id')
                    ->where('bookings.restaurant_id', $data['restaurant_id'])
                    ->where('bookings.status', 'confirmed')
                    ->where('booking_table_types.table_type_id', $typeId)
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('bookings.start_time', [$start, $end])
                            ->orWhereBetween('bookings.end_time', [$start, $end])
                            ->orWhere(function ($q2) use ($start, $end) {
                                $q2->where('bookings.start_time', '<', $start)
                                    ->where('bookings.end_time', '>', $end);
                            });
                    })
                    ->sum('booking_table_types.tables_count');

                $tableType = TableType::findOrFail($typeId);
                $available = $tableType->tables_count - $existingCount;

                if ($requestedCount > $available) {
                    return response()->json([
                        'error_code' => BookingErrorCode::NotEnoughTablesAvailable->value,
                        'message' => "Not enough tables of type '{$typeId}' available.",
                    ], 422);
                }

                $totalPlaces += $tableType->places_count * $requestedCount;
            }

            $restaurant = Restaurant::findOrFail($data['restaurant_id']);
            if ($restaurant->max_booking_places !== null && $totalPlaces > $restaurant->max_booking_places) {
                return response()->json([
                    'error_code' => BookingErrorCode::MaxPlacesExceeded->value,
                    'message' => "The total number of places exceeds the restaurant's limit ({$totalPlaces} of {$restaurant->max_booking_places}).",
                ], 422);
            }

            $booking = Booking::create([
                'user_id' => $userId,
                'restaurant_id' => $data['restaurant_id'],
                'start_time' => $start,
                'end_time' => $end,
            ]);

            foreach ($data['table_types'] as $typeRequest) {
                $booking->tableTypes()->attach($typeRequest['id'], [
                    'tables_count' => $typeRequest['count']
                ]);
            }

            DB::commit();
            return response()->json($booking->load('tableTypes'), 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error_code' => BookingErrorCode::UnknownError->value,
                'message' => 'Unexpected server error.',
                'debug' => app()->isLocal() ? $e->getMessage() : null,
            ], 500);
        }
    }


    public function cancel(Booking $booking, Request $request)
    {
        if ($booking->user_id != $request->attributes->get('user_id')) {
            abort(403);
        }

        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages([
                'status' => 'The booking is already cancelled.'
            ]);
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return $booking;
    }

    public function active(int $restaurant, Request $request)
    {
        $userId = $request->attributes->get('user_id');
        $activeBooking = Booking::where('user_id', $userId)
            ->where('restaurant_id', $restaurant)
            ->where('status', 'confirmed')
            ->where('end_time', '>', now())
            ->first();
        return response()->json(['active_booking' => $activeBooking]);

    }
}
