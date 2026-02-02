<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'chasis','placa','uso','tipo','accionado',
        'marca','modelo','linea',
        'no_serie','no_motor',
        'color','cilindrada','cilindros','tonelaje',
        'asientos','ejes','puertas',
    ];

    protected $casts = [
        'cilindrada' => 'integer',
        'cilindros'  => 'integer',
        'asientos'   => 'integer',
        'ejes'       => 'integer',
        'puertas'    => 'integer',
        'tonelaje'   => 'decimal:2',
    ];
}
