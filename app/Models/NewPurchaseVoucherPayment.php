<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPurchaseVoucherPayment extends Model{
    protected $table = 'new_purchase_voucher_payment';
    protected $fillable = ['pv_no','pv_date','amount','status','username','date','new_purchase_voucher_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
