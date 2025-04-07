<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTableType;
use App\Services\RabbitMQPublisher;
use Illuminate\Http\Request;

class RestaurantTableTypeController extends Controller
{

    public function __construct(private RabbitMQPublisher $publisher) {}

    public function store(Request $request, $restaurantId)
    {
        $validated = $request->validate([
            'places_count' => 'required|integer|min:1',
            'tables_count' => 'required|integer|min:1',
        ]);

        $tableType = RestaurantTableType::create([
            'restaurant_id' => $restaurantId,
            'places_count' => $validated['places_count'],
            'tables_count' => $validated['tables_count'],
        ]);

        $this->publisher->publishTableTypeEvent('created', $tableType->toArray());

        return response()->json($tableType, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'places_count' => 'sometimes|integer|min:1',
            'tables_count' => 'sometimes|integer|min:1',
        ]);

        $tableType = RestaurantTableType::findOrFail($id);
        $tableType->update($validated);

        $this->publisher->publishTableTypeEvent('updated', $tableType->toArray());

        return response()->json($tableType);
    }

    public function destroy($id)
    {
        $tableType = RestaurantTableType::findOrFail($id);
        $tableType->delete();

        $this->publisher->publishTableTypeEvent('deleted', ['id' => $id]);

        return response()->json(['message' => 'Deleted successfully']);
    }
}
