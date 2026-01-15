<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsFrequency extends Model{
    protected $table = 'assets_frequency';
    protected $fillable = ['frequency','username','status','date','time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
