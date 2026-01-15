<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionWorkOrderData extends Model
{
    public $connection = "mysql2";
    protected $table = "production_work_order_data";
    public $timestamps = false;
}
