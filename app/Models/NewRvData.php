<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewRvData extends Model{
    protected $table = 'new_rv_data';
    protected $fillable = ['master_id','rv_no','acc_id','debit_credit','amount','status','rv_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
