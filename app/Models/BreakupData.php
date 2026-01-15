<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakupData extends Model{
    protected $table = 'breakup_data';
    protected $fillable = ['main_id','jv_id','voucher_no','voucher_date','pv_id','slip_no','debit_credit','amount','supplier_id','breakup_type','advnace','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
