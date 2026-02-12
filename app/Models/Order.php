<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id','vehicle_id','status','notes',
        'labor_total','parts_total','grand_total','created_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspection()
    {
        return $this->hasOne(OrderInspection::class);
    }
}
