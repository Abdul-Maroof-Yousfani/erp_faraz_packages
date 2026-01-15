<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model{
    protected $table = 'type';
    protected $fillable = ['name','status','date','username'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;
}
