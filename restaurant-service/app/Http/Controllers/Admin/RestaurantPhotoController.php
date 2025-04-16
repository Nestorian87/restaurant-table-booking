<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RestaurantPhotoController extends Controller
{
    public function store(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10000',
        ]);

        $uploaded = [];

        foreach ($request->file('photos', []) as $file) {
            $path = $file->store('uploads/restaurants', 'public');
            $photo = $restaurant->photos()->create(['path' => $path]);
            $uploaded[] = $photo;
        }

        return response()->json([
            'message' => 'Photos uploaded successfully.',
            'photos' => $uploaded,
        ]);
    }

    public function destroy(RestaurantPhoto $photo)
    {
        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return response()->json([
            'message' => 'Photo deleted successfully.'
        ]);
    }
}
