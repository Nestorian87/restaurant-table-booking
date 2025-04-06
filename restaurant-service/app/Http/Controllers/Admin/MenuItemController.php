<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        return MenuItem::whereHas('category', function ($q) use ($restaurant) {
            $q->where('restaurant_id', $restaurant->id);
        })->with('category')->get();
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'menu_category_id' => 'required|numeric|exists:menu_categories,id',
            'photo' => 'nullable|image|max:10000',
            'volume' => 'required|numeric|min:0',
            'unit' => 'required|string|' . 'in:' . implode(',', config('menu.allowed_units')),
        ]);

        MenuCategory::where('id', $validated['menu_category_id'])
            ->where('restaurant_id', $restaurant->id)
            ->firstOrFail();

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('uploads/menu', 'public');
        }

        $validated['restaurant_id'] = $restaurant->id;

        $menuItem = MenuItem::create($validated);

        return response()->json([
            'message' => 'Menu item created successfully',
            'data' => $menuItem,
        ], 201);
    }


    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:300',
            'price' => 'nullable|numeric|min:1',
            'menu_category_id' => 'nullable|numeric|exists:menu_categories,id',
            'photo' => 'nullable|image|max:10000',
            'volume' => 'nullable|numeric|min:1',
            'unit' => 'nullable|string|' . 'in:' . implode(',', config('menu.allowed_units')),
        ]);

        if ($request->hasFile('photo')) {
            if ($menuItem->photo) {
                Storage::disk('public')->delete($menuItem->photo);
            }
            $validated['photo'] = $request->file('photo')->store('uploads/menu', 'public');
        }

        $menuItem->update($validated);

        return response()->json([
            'message' => 'Menu item updated successfully',
            'data' => $menuItem,
        ]);
    }

    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->photo) {
            Storage::disk('public')->delete($menuItem->photo);
        }

        $menuItem->delete();

        return response()->json(null, 204);
    }
}

