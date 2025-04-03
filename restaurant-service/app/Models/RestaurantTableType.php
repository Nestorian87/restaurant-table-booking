<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantTableType extends Model
{
    protected $fillable = ['restaurant_id', 'places_count', 'tables_count'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
