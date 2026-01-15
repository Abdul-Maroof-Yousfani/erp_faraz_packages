<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreChallanData extends Model{
    protected $table = 'store_challan_data';
    protected $fillable = ['store_challan_no','store_challan_date','demand_no','demand_date','category_id','sub_item_id','issue_qty','store_challan_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
