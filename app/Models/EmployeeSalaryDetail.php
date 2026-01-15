<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryDetail extends Model{
    protected $table = 'employee_salary_detail';
    protected $fillable = ['emp_id','last_drawn_salary','last_drawn_benefits','expected_salary','expected_benefits','notice_period','possbile_doj','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

