<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionCuttingAndPacking extends Model
{
    public $connection = "mysql2";
    protected $table = "production_cutting_and_packing";
    protected $guarded = [];
    public $timestamps = false;

    

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

    public function printedRoll()
    {
        return $this->belongsTo(ProductionRollPrinting::class, 'printed_rolling_id');
    }


}
