<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewJvData extends Model{
    protected $table = 'new_jv_data';
    // protected $fillable = ['master_id','jv_no','acc_id','debit_credit','amount','status','jv_status'];
    protected $guarded = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
