<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAllocation1 extends Model{
    protected $table = 'department_allocation';
  //  protected $fillable = ['designation_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
