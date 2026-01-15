<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model{
    protected $table = 'invoice';
  //  protected $fillable = ['inv_no','inv_date','ship_to','bill_to_client_id','description','discount_percent','discount_amount','sales_tax_percent','sales_tax_amount','inv_status','status','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
