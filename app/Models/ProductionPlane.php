<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPlane extends Model
{
    public $connection = "mysql2";
    protected $table = "production_plane";
    protected $guarded = [];
}
