<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model{
    protected $table = 'branch';
    protected $fillable = ['client_id','branch_name','ntn','strn','address','status','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

