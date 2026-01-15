<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subitem extends Model{
	protected $connection = 'mysql2';
	protected $table = 'subitem';
	protected $fillable = ['supplier_id','sub_ic','main_ic_id','acc_id','department_id','pack_size','kit_amount','tax_able','sales_tax_rate','time','date','action','username','status','trail_id','branch_id','type','no_test','uom',
	'primary_pack_type','uom2','secondary_pack_size','secondary_pack_type','saleOutUnitQuantityPrice','allowDiscountUnitQuantity','completeBoxPrice','completeBoxDiscount','allowTestingQuantity','inventoryStockEveryTime','totalQuantityinOnePack','stockType','itemType','remark'];
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function uomData()
	{
		return $this->belongsTo(UOM::class,'uom','id');
	}
}


