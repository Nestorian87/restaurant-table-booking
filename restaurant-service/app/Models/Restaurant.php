<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'location', 'phone', 'description', 'max_booking_places'];

    protected $appends = ['has_menu'];

    public function getHasMenuAttribute(): bool
    {
        return $this->menuItems()->exists();
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function photos()
    {
        return $this->hasMany(RestaurantPhoto::class);
    }

    public function tableTypes()
    {
        return $this->hasMany(RestaurantTableType::class)->orderBy('places_count');
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
