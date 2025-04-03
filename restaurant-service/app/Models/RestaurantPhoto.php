<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantPhoto extends Model
{
    protected $fillable = ['restaurant_id', 'path'];
    protected $appends = ['url'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getUrlAttribute(): string
    {
        return config('app.gateway_storage_url') . '/' . $this->path;
    }

}
