<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTaskData extends Model{
    protected $table = 'daily_task_data';
    protected $fillable = ['client','description','acc_officer','vendor','region','status','username','date','daily_task_id','action'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
