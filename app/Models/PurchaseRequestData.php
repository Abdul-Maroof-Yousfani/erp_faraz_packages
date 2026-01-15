<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestData extends Model{
    protected $table = 'purchase_request_data';
    protected $fillable = ['purchase_request_no','purchase_request_date','demand_no','demand_date','demand_type','demand_send_type','category_id','sub_item_id','purchase_request_qty','rate','sub_total','purchase_request_status','status','date','time','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
