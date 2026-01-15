<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPlaneRecipe extends Model
{
    public $connection = "mysql2";
    protected $table = "production_plane_recipe";
    protected $guarded = [];
    public $timestamps = false;
}
