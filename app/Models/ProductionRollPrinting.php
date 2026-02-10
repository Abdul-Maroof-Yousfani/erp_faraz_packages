<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionRollPrinting extends Model
{
    public $connection = "mysql2";
    protected $table = "production_roll_printing";
    protected $guarded = [];
    public $timestamps = false;

    

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

    public function productionRoll()
    {
        return $this->belongsTo(ProductionRolling::class, 'production_rolling_id');
    }


}
