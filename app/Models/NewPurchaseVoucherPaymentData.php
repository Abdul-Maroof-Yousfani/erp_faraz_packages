<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPurchaseVoucherPaymentData extends Model{
    protected $table = 'new_purchase_voucher_payment_data';
    protected $fillable = ['new_purchase_voucher_payment_id','new_purchase_voucher_id','amount','date','status','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
