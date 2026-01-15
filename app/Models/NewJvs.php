<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewJvs extends Model{
    protected $table = 'new_jvs';
    protected $fillable = ['jv_no','jv_date','description','date','status','jv_status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
