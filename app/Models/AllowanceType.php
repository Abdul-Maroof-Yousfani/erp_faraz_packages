<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowanceType extends Model{
    protected $table = 'allowance_type';
    protected $fillable = ['allowance_type','status','username','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
