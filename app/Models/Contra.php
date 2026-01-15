<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contra extends Model{
    protected $table = 'contra';
    protected $fillable = ['cv_date','cv_no','slip_no','rv_status','cheque_no','cheque_date','post_dated','description','username','status','date','time','approve_username','delete_username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
