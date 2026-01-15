<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LOGACTIVITY extends Model{
    protected $table = 'logactivity';
    protected $fillable = ['voucher_no','v_date','action','table_name','client_id','status','username','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
