<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MakeProduct extends Model
{
    protected $connection = "mysql2";
    protected $guarded = [];

    public function productDatas()
    {
        return $this->hasMany(MakeProductData::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
