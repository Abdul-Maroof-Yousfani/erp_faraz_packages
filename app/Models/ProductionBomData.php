<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBomData extends Model
{
    public $connection = "mysql2";
    protected $table = "production_bom_data_indirect_material";
    protected $guarded = [];

    public function subItem()
    {
        return $this->hasOne(Subitem::class, 'id', 'item_id');
    }
    public function workstation()
    {
        return $this->hasOne(WorkStation::class, 'id', 'work_station_id');
    }
}
