<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeQualification extends Model{
    protected $table = 'employee_qualification';
    protected $fillable = ['emp_id','degree_type','school_college_university','year_of_passing','grade_div_cgpa','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

