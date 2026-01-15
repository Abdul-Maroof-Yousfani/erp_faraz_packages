<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model{
    protected $table = 'region';
    protected $fillable = ['region_code','region_name','status','created_date','created_time','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
