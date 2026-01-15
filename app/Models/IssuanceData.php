<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuanceData extends Model{
    protected $table = 'issuance_data';
    protected $fillable = ['master_id','iss_no','category_id','sub_item_id','joborder_id','qty','status','issuance_status','created_date','created_time','username','region'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
