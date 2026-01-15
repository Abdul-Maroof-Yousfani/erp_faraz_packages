<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model{
    protected $table = 'estimate';
    protected $fillable = ['item','qty','uom','stock_value','job_order_data_id','status','username','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

