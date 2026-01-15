<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTrackingData extends Model{
    protected $table = 'job_tracking_data';
    protected $fillable = ['task','task_assigned','task_target_date','task_completed_date','resource','remarks','job_tracking_id','status','username','date'];
    protected $primaryKey = 'job_tracking_data_id';
    public $timestamps = false;
}
