<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model{
    protected $table = 'purchase_request';
    protected $fillable = ['slip_no','purchase_request_no','purchase_request_date','department_id','supplier_id','description','purchase_request_status','currency_id','currency_rate','status','date','time','username','approve_username','delete_username','sales_tax_acc_id', 'total_amount'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(SubDepartment::class, 'agent', 'id');
    }
}
