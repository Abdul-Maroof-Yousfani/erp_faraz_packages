<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewPvData extends Model{
    protected $table = 'new_pv_data';
    // protected $fillable = ['master_id','pv_no','pv_date','acc_id','debit_credit','amount','date','status'];
    protected $guarded  = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
