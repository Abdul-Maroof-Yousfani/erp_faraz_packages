<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contra_data extends Model{
    protected $table = 'contra_data';
    protected $fillable = ['master_id','cv_no','cv_date','acc_id','description','debit_credit','amount','rv_status','time','date','status','username','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
