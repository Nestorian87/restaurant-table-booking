<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RestaurantAvailableTablesController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $start = Carbon::parse($request->input('start_time'));
        $end = Carbon::parse($request->input('end_time'));

        $result = [];

        foreach ($restaurant->tableTypes as $type) {
            $existingCount = DB::table('booking_table_types')
                ->join('bookings', 'booking_table_types.booking_id', '=', 'bookings.id')
                ->where('bookings.restaurant_id', $restaurant->id)
                ->where('bookings.status', 'confirmed')
                ->where('booking_table_types.table_type_id', $type->id)
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween('bookings.start_time', [$start, $end])
                        ->orWhereBetween('bookings.end_time', [$start, $end])
                        ->orWhere(function ($q2) use ($start, $end) {
                            $q2->where('bookings.start_time', '<', $start)
                                ->where('bookings.end_time', '>', $end);
                        });
                })
                ->sum('booking_table_types.tables_count');

            $available = $type->tables_count - $existingCount;

            $result[] = [
                'id' => $type->id,
                'places_count' => $type->places_count,
                'total' => $type->tables_count,
                'available' => max(0, $available),
            ];
        }

        return response()->json($result);
    }
}
