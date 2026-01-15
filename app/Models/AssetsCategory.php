<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsCategory extends Model{
    protected $table = 'assets_category';
    protected $fillable = ['category_name','category_abbreviation','username','status','display_name'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
