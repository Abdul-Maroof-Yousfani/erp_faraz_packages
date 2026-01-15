<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintProduct extends Model{
    protected $table = 'complaint_product';
    protected $fillable = ['product','front','p_left','p_right','back','complaint_id','status','date','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
