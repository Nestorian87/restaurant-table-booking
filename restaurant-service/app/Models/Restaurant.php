<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = ['name', 'location', 'phone', 'description', 'max_booking_places'];

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
        return $this->hasMany(RestaurantTableType::class);
    }

}
