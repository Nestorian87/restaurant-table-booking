<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserRestaurantController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $page = (int) $request->get('page', 1);

        $cacheKey = "restaurants_index_page_{$page}_per_{$perPage}";

        $restaurants = Cache::remember($cacheKey, now()->addSeconds(30), function () use ($perPage) {
            return Restaurant::with('workingHours', 'photos', 'tableTypes')->paginate($perPage);
        });

        return RestaurantResource::collection($restaurants);
    }

    public function show(Restaurant $restaurant): Restaurant
    {
        $restaurant->increment('views_count');

        $cacheKey = "restaurant_details_{$restaurant->id}";

        return Cache::remember($cacheKey, now()->addSeconds(30), function () use ($restaurant) {
            return $restaurant->load('workingHours', 'photos', 'tableTypes');
        });
    }
}
