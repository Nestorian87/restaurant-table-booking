<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Services\RabbitMQPublisher;
use Illuminate\Http\Request;

class AdminRestaurantController extends Controller
{
    public function __construct(protected RabbitMQPublisher $publisher)
    {
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $restaurants = Restaurant::with('workingHours', 'photos', 'tableTypes')->paginate($perPage);

        return RestaurantResource::collection($restaurants);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'max_booking_places' => 'nullable|integer|min:1',
            'working_hours' => 'array',
        ]);

        $restaurant = Restaurant::create($validated);


        if ($request->has('working_hours')) {
            foreach ($request->working_hours as $item) {
                $restaurant->workingHours()->create($item);
            }
        }

        $this->publisher->publishRestaurantEvent('created', [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'max_booking_places' => $restaurant->max_booking_places,
            'working_hours' => $restaurant->workingHours()->get()->toArray(),
        ]);


        return response()->json($restaurant->load('workingHours'), 201);
    }

    public function show(Restaurant $restaurant)
    {
        return $restaurant->load('workingHours', 'photos', 'tableTypes');
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'string|max:100',
            'location' => 'string|max:255',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'working_hours' => 'array',
            'max_booking_places' => 'nullable|integer|min:1',
        ]);

        $restaurant->update($validated);

        if ($request->has('working_hours')) {
            $restaurant->workingHours()->delete();
            foreach ($request->working_hours as $item) {
                $restaurant->workingHours()->create($item);
            }
        }

        $this->publisher->publishRestaurantEvent('updated', [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'max_booking_places' => $restaurant->max_booking_places,
            'working_hours' => $restaurant->workingHours()->get()->toArray(),
        ]);

        return response()->json($restaurant->load('workingHours'));
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        $this->publisher->publishRestaurantEvent('deleted', [
            'id' => $restaurant->id,
        ]);

        return response()->json(['message' => 'Restaurant deleted']);
    }
}
