<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetsSubCategory extends Model{
    protected $table = 'assets_sub_category';
    protected $fillable = ['category_id','priority_id','sub_category_name','sub_category_abbreviation','username','status'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
