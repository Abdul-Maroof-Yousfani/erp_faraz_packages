<?php

namespace App\Models;

use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;
use Illuminate\Database\Eloquent\Model;

class ProductionGalaCutting extends Model
{
    public $connection = "mysql2";
    protected $table = "production_gala_cutting";
    protected $guarded = [];
    public $timestamps = false;



    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

    public function cuttingAndSealing()
    {
        return $this->belongsTo(ProductionCuttingAndSealing::class, 'cutting_sealing_id');
    }

    public function packing()
    {
        return $this->hasMany(ProductionPacking::class, 'gala_cutting_id');
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
