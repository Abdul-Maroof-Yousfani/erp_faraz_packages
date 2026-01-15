<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_Order extends Model{
    protected $table = 'sales_order';
    protected $connection =  'mysql2';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    // protected $primaryKey = 'id';
    public $timestamps = false;

    public function customer()
    {
        return $this->hasOne(Customer::class,'id','buyers_id');
    }
    public function saleOrderData()
    {
        return $this->hasMany(Sales_Order_Data::class,'master_id','id');
    }

}

