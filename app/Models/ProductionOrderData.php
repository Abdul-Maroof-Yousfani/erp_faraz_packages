<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrderData extends Model
{
    public $connection = "mysql2";
    protected $table = "production_request_data";
    protected $guarded = [];
    public $timestamps = false;

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }
    
}
