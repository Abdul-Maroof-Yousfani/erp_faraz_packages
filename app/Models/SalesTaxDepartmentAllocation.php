<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTaxDepartmentAllocation extends Model{
    protected $table = 'sales_tax_department_allocation';
    //  protected $fillable = ['designation_name','status','username','action','date','time','company_id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
