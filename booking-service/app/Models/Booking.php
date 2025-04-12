<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'status',
        'restaurant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tableTypes()
    {
        return $this->belongsToMany(TableType::class, 'booking_table_types')
            ->withPivot('tables_count')
            ->withTimestamps();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
