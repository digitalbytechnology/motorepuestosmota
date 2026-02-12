<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInspection extends Model
{
    protected $fillable = [
        'order_id',
        'luz_silvin_bajas','luz_silvin_altas','luz_stop','pidevias','bocina','neblineras','alarma',
        'aceite_pct','gasolina_level','arranca',
        'check_engine','check_engine_detalle','odometro',
        'frenos','llantas','llantas_profundidad','espejos',
        'doc_tarjeta','doc_copia_llave','accesorios_recibidos',
        'observaciones','firma_path','created_by',
    ];

    protected $casts = [
        'luz_silvin_bajas' => 'boolean',
        'luz_silvin_altas' => 'boolean',
        'luz_stop' => 'boolean',
        'pidevias' => 'boolean',
        'bocina' => 'boolean',
        'neblineras' => 'boolean',
        'alarma' => 'boolean',
        'arranca' => 'boolean',
        'check_engine' => 'boolean',
        'espejos' => 'boolean',
        'doc_tarjeta' => 'boolean',
        'doc_copia_llave' => 'boolean',
        'aceite_pct' => 'integer',
        'odometro' => 'integer',
    ];

    public function photos()
    {
        return $this->hasMany(OrderInspectionPhoto::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
