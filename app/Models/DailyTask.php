<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model{
    protected $table = 'daily_task';
    protected $fillable = ['task_date','status','username','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $connection = 'mysql2';
}
