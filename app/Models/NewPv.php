<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newpv extends Model{
    protected $table = 'new_pv';
    protected $fillable = ['pv_no','pv_date','bill_no','bill_date','cheque_no','cheque_date','payment_type','description','date','status','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
