<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTracking extends Model{
    protected $table = 'job_tracking';
    protected $fillable = ['customer','branch_id','customer_job','region','job_description','job_tracking','job_tracking_date','city','status','username','date'];
    protected $primaryKey = 'job_tracking_id';
    public $timestamps = false;
}

