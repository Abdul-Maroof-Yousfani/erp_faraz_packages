<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallan extends Model{
    protected $table = 'store_challan';
    protected $fillable = ['slip_no','store_challan_no','store_challan_date','sub_department_id','description','store_challan_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
