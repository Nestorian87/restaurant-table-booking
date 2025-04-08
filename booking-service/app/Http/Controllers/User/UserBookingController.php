<?php

namespace App\Http\Controllers\User;

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

        return Booking::with(['tableTypes', 'review'])
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
        ]);

        $start = Carbon::parse($data['start_time']);
        $end = Carbon::parse($data['end_time']);

        if ($start->isPast()) {
            throw ValidationException::withMessages([
                'start_time' => 'Неможливо забронювати час у минулому.'
            ]);
        }

        $userId = $request->attributes->get('user_id');
        $activeExists = Booking::where('user_id', $userId)
            ->where('restaurant_id', $data['restaurant_id'])
            ->where('end_time', '>', Carbon::now())
            ->where('status', 'confirmed')
            ->exists();

        if ($activeExists) {
            throw ValidationException::withMessages([
                'restaurant_id' => 'У вас вже є активне бронювання у цьому ресторані.'
            ]);
        }

        if ($start->isSameDay($end) === false) {
            throw ValidationException::withMessages(['end_time' => 'Бронювання повинно бути в межах одного дня.']); //TODO error code
        }

        $weekday = $start->dayOfWeekIso % 7;
        $workingHour = WorkingHour::where('restaurant_id', $data['restaurant_id'])
            ->where('day', $weekday)
            ->first();

        if (!$workingHour) {
            throw ValidationException::withMessages(['start_time' => 'Ресторан не працює цього дня.']);
        }

        $startTimeStr = $start->format('H:i:s');
        $endTimeStr = $end->format('H:i:s');

        if ($startTimeStr < $workingHour->open_time || $endTimeStr > $workingHour->close_time) {
            throw ValidationException::withMessages(['start_time' => 'Бронювання виходить за межі робочого часу ресторану.']);
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
                    throw ValidationException::withMessages([
                        'table_types' => "Недостатньо доступних столів типу '{$typeId}'"
                    ]);
                }


                $totalPlaces += $tableType->places_count * $typeRequest['count'];
            }

            $restaurant = Restaurant::findOrFail($data['restaurant_id']);
            if ($restaurant->max_booking_places !== null && $totalPlaces > $restaurant->max_booking_places) {
                throw ValidationException::withMessages([
                    'table_types' => "Максимальна кількість місць для бронювання перевищена ({$totalPlaces} з {$restaurant->max_booking_places})."
                ]);
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
            throw $e;
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
}
