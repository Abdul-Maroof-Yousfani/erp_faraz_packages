<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewRvs extends Model{
    protected $table = 'new_rvs';
    protected $fillable = ['rv_no','rv_date','ref_bill_no','cheque_no','cheque_date','description','date','status','rv_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
