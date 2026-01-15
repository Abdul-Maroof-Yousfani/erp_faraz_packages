<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceData extends Model{
    protected $table = 'inv_data';
    protected $fillable = ['master_id','product_id','description','uom_id','qty','rate','amount','status','inv_status','date','time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
