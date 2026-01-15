<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issuance extends Model{
    protected $table = 'issuance';
    protected $fillable = ['iss_no','iss_date','description','status','created_date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
