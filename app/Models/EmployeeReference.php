<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeReference extends Model{
    protected $table = 'employee_reference';
    protected $fillable = ['emp_id','reference_name','reference_designation','reference_address','reference_country','reference_relationship','username','status','time','date'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}

