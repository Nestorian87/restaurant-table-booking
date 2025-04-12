<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantMenuController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $menuItems = MenuItem::where('restaurant_id', $restaurant->id)->get();
        $itemsByCategory = $menuItems->groupBy('menu_category_id');
        $categories = MenuCategory::where('restaurant_id', $restaurant->id)
            ->get()
            ->filter(function ($category) use ($itemsByCategory) {
                return $itemsByCategory->has($category->id);
            })
            ->values();

        return response()->json([
            'categories' => $categories,
            'items' => $menuItems,
            'restaurant_name' => $restaurant->name,
        ]);
    }
}

