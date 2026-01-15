<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCategory extends Model{
    protected $table = 'employee_category';
    protected $fillable = ['employee_category_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;


}

