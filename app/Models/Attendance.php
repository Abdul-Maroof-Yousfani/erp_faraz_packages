<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model{
    protected $table = 'attendance';
    protected $fillable = ['emp_code','emp_name','attendance_date','clock_in','clock_out' ,'attendance_type', 'day', 'month', 'year', 'present_days', 'absent_days', 'overtime', 'type', 'status', 'date', 'time'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
