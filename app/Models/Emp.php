<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emp extends Model{
    protected $table = 'emp';
    protected $fillable = ['id','emp_name','status',];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
