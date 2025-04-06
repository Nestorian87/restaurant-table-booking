<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        return $restaurant->menuCategories()->get();
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['restaurant_id'] = $restaurant->id;

        $category = MenuCategory::create($validated);

        return response()->json([
            'message' => 'Menu category created successfully',
            'data' => $category,
        ], 201);
    }


    public function update(Request $request, MenuCategory $menuCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $menuCategory->update($validated);

        return response()->json([
            'message' => 'Menu category updated successfully',
            'data' => $menuCategory,
        ]);
    }

    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->delete();

        return response()->json(null, 204);
    }
}

