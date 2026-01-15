<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidTo extends Model{
    protected $table = 'paid_to';
    protected $fillable = ['name','mobil_no','username','status','date','username'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
