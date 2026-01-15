<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptNote extends Model{
    protected $table = 'goods_receipt_note';
    protected $fillable = ['grn_no','grn_date','pr_no','pr_date','sub_department_id','supplier_id','main_description','invoice_no','status','grn_status','username','date','time','approve_username','delete_username','file_path'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
