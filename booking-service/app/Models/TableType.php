<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TableType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bookingTableTypes()
    {
        return $this->hasMany(BookingTableType::class);
    }
}
