<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        return Restaurant::with('workingHours')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'working_hours' => 'array',
        ]);

        $restaurant = Restaurant::create($validated);

        if ($request->has('working_hours')) {
            foreach ($request->working_hours as $item) {
                $restaurant->workingHours()->create($item);
            }
        }

        return response()->json($restaurant->load('workingHours'), 201);
    }

    public function show(Restaurant $restaurant)
    {
        return $restaurant->load('workingHours');
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'string|max:100',
            'location' => 'string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'working_hours' => 'array',
        ]);

        $restaurant->update($validated);

        if ($request->has('working_hours')) {
            $restaurant->workingHours()->delete();
            foreach ($request->working_hours as $item) {
                $restaurant->workingHours()->create($item);
            }
        }

        return response()->json($restaurant->load('workingHours'));
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return response()->json(['message' => 'Restaurant deleted']);
    }
}
