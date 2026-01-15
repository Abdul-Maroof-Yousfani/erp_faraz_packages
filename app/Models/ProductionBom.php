<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBom extends Model
{
    public $connection = "mysql2";
    protected $table = "production_bom";
    protected $guarded = [];
    public $timestamps = false;

    public function subItem()
    {
        return $this->belongsTo(Subitem::class, 'finish_goods');
    }
    public function recipeDatas()
    {
       return $this->hasMany(ProductionBomData::class,'main_id');
    }
}
