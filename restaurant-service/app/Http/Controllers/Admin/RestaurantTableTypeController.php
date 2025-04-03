<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantTableType;
use Illuminate\Http\Request;

class RestaurantTableTypeController extends Controller
{
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

        return response()->json($tableType);
    }

    public function destroy($id)
    {
        $tableType = RestaurantTableType::findOrFail($id);
        $tableType->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
