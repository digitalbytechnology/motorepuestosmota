<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInspectionPhoto extends Model
{
    protected $fillable = ['order_inspection_id','path','annotations','notes','flattened_path'];

    protected $casts = ['annotations' => 'array'];

    public function inspection()
    {
        return $this->belongsTo(OrderInspection::class, 'order_inspection_id');
    }
}
