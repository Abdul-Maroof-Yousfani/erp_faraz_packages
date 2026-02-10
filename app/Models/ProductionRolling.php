<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionRolling extends Model
{
    public $connection = "mysql2";
    protected $table = "production_rolling";
    protected $guarded = [];
    public $timestamps = false;

    

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function productionMixing()
    {
        return $this->belongsTo(ProductionMixture::class, 'production_mixture_id');
    }
    

}
