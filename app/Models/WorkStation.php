<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkStation extends Model
{
    public $connection = "mysql2";
    protected $table = "work_station";
    protected $guarded = [];
    public $timestamps = false;
}
