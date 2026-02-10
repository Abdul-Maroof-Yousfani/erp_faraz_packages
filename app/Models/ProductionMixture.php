<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMixture extends Model
{
    public $connection = "mysql2";
    protected $table = "production_mixture";
    protected $guarded = [];
    public $timestamps = false;

    public function mixtureData()
    {
        return $this->hasMany(ProductionMixtureData::class, 'production_mixture_id');
    }

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

}
