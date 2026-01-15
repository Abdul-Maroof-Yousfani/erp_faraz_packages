<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionWorkOrder extends Model
{
    public $connection = "mysql2";
    protected $table = "production_work_order";
    public $timestamps = false;
}
