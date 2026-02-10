<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    public $connection = "mysql2";
    protected $table = "production_request";
    protected $guarded = [];
    public $timestamps = false;

    public function PO_Data()
    {
        return $this->hasMany(ProductionOrderData::class, 'master_id');
    }

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

}
