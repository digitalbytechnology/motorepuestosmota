<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'date',
        'time',
        'name',
        'phone',
        'observations',
        'attended',
    ];

    protected $casts = [
        'date' => 'date',
        'attended' => 'boolean',
    ];
}
