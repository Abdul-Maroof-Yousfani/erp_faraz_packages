<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTaxInvoice extends Model{
    protected $table = 'sales_tax_invoice';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;




        public function currencyRelation()
        {
            return $this->belongsTo(Currency::class, 'currency');
        }
}
