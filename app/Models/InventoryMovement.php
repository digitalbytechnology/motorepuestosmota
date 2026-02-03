<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = ['part_id','type','qty','unit_cost','note','user_id'];
    protected $casts = ['qty' => 'integer', 'unit_cost' => 'decimal:2'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
