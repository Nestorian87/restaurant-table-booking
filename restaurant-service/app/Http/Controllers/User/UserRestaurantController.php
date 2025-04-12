<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use App\Services\RabbitMQPublisher;
use Illuminate\Http\Request;

class UserRestaurantController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $restaurants = Restaurant::with('workingHours', 'photos', 'tableTypes')->paginate($perPage);

        return RestaurantResource::collection($restaurants);
    }

    public function show(Restaurant $restaurant): Restaurant
    {
        return $restaurant->load('workingHours', 'photos', 'tableTypes');
    }
}
