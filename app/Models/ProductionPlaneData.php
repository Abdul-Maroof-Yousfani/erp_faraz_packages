<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPlaneData extends Model
{
    public $connection = "mysql2";
    protected $table = "production_plane_data";
    protected $guarded = [];
}
