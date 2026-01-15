<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'brands';
    protected $guarded = [];
}
