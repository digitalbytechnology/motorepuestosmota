<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'sku','nombre','descripcion','aplica_a',
        'category_id','marca','costo','precio',
        'stock','stock_min','activo'
    ];

    protected $casts = [
        'costo' => 'decimal:2',
        'precio' => 'decimal:2',
        'stock' => 'integer',
        'stock_min' => 'integer',
        'activo' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(PartCategory::class, 'category_id');
    }

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
