<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMixtureData extends Model
{
    public $connection = "mysql2";
    protected $table = "production_mixture_data";
    protected $guarded = [];
    public $timestamps = false;

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }
    
}
