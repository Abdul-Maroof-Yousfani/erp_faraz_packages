<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrderData extends Model{
    protected $table = 'job_order_data';
    protected $fillable = ['product','width','height','depth','quantity','job_order_id','status','username','date'];
    protected $primaryKey = 'job_order_data_id';
    public $timestamps = false;
}
