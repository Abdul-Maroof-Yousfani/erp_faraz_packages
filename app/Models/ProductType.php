<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model{
    protected $table = 'product_type';
    protected $fillable = ['type','status','date','username'];
    protected $primaryKey = 'product_type_id';
    public $timestamps = false;
}
