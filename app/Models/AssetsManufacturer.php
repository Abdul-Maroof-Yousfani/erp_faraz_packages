<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsManufacturer extends Model{
    protected $table = 'assets_manufacturer';
    protected $fillable = ['manufacturer_name','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
