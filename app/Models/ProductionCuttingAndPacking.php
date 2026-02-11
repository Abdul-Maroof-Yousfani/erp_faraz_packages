<?php

namespace App\Models;

use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;
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

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
}
