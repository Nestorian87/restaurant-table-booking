<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $guarded = [];

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function tableTypes()
    {
        return $this->hasMany(TableType::class);
    }
}
