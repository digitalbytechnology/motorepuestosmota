<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentDayLimit extends Model
{
    protected $fillable = ['date', 'max_per_day'];

    protected $casts = [
        'date' => 'date',
    ];
}
