<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    protected $table = 'product';
    protected $fillable = ['p_name','acc_id','type_id','p_status','p_date','p_username'];
    protected $primaryKey = 'product_id';
    public $timestamps = false;
}
