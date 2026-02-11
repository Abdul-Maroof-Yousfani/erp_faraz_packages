<?php

namespace App\Models;

use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;
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

    public function cuttingAndPackings()
    {
        return $this->hasMany(ProductionCuttingAndPacking::class, 'printed_rolling_id');
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
