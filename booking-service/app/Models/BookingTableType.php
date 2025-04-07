<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingTableType extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'table_type_id', 'tables_count'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tableType()
    {
        return $this->belongsTo(TableType::class);
    }
}
