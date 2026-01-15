<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanReturnData extends Model{
    protected $table = 'store_challan_return_data';
    protected $fillable = ['store_challan_return_no','store_challan_return_date','store_challan_no','store_challan_date','category_id','sub_item_id','return_qty','store_challan_return_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
