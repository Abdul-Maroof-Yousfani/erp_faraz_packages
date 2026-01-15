<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanReturn extends Model{
    protected $table = 'store_challan_return';
    protected $fillable = ['slip_no','store_challan_return_no','store_challan_return_date','sub_department_id','description','store_challan_return_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
