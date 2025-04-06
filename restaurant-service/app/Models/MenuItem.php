<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'photo',
        'menu_category_id',
        'unit',
        'volume',
        'restaurant_id',
    ];

    protected $appends = ['photo_url'];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }


    public function getPhotoUrlAttribute(): string
    {
        if (empty($this->photo)) {
            return '';
        }
        return config('app.gateway_storage_url') . '/' . $this->photo;
    }
}
