<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    protected $fillable = ['nombre','activo'];
    protected $casts = ['activo' => 'boolean'];

    public function parts()
    {
        return $this->hasMany(Part::class, 'category_id');
    }
}
