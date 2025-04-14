<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class RestaurantMenuController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $cacheKey = "restaurant_menu_{$restaurant->id}";

        $data = Cache::remember($cacheKey, now()->addSeconds(30), function () use ($restaurant) {
            $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->get();
            $itemsByCategory = $menuItems->groupBy('menu_category_id');

            $categories = MenuCategory::where('restaurant_id', $restaurant->id)
                ->get()
                ->filter(function ($category) use ($itemsByCategory) {
                    return $itemsByCategory->has($category->id);
                })
                ->values();

            return [
                'categories' => $categories,
                'items' => $menuItems,
                'restaurant_name' => $restaurant->name,
            ];
        });

        return response()->json($data);
    }
}

