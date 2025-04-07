<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'text', 'rating_kitchen', 'rating_interior', 'rating_service', 'rating_atmosphere'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function reactions()
    {
        return $this->hasMany(ReviewUserReaction::class);
    }
}
