<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenterDepartmentAllocation extends Model{
    protected $table = 'cost_center_department_allocation';
    //  protected $fillable = ['designation_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
