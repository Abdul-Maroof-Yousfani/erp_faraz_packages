<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ShiftType;

class ProductionMixture extends Model
{
    public $connection = "mysql2";
    protected $table = "production_mixture";
    protected $guarded = [];
    public $timestamps = false;

    public function mixtureData()
    {
        return $this->hasMany(ProductionMixtureData::class, 'production_mixture_id');
    }

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'item_id');
    }

    public function productionOrder()
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function shift()
    {
        return $this->belongsTo(ShiftType::class, 'shift_id');
    }

    public function productionRollings()
    {
        return $this->hasMany(ProductionRolling::class, 'production_mixture_id');
    }
}
