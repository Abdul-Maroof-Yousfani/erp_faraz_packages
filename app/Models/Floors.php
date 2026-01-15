<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floors extends Model{
    protected $table = 'floors';
    protected $fillable = ['floor','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
