<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'sub_category';
    protected $fillable = ['category_id', 'sub_category_name', 'acc_id', 'status', 'username', 'created_date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
