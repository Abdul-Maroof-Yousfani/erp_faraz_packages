<?php

namespace App\Models;

use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;
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

    public function printings()
    {
        return $this->hasMany(ProductionRollPrinting::class, 'production_rolling_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    // public function shift()
    // {
    //     return $this->belongsTo(ShiftType::class, 'shift_id');
    // }

}
