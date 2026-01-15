<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsDesignedLife extends Model{
    protected $table = 'assets_designed_life';
    protected $fillable = ['useful_life_name','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
