<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    protected $table = 'delivery_note';

    protected $fillable = [
    'master_id', 'so_id', 'so_data_id', 'desc', 'gd_no', 'gd_date',
    'item_id', 'warehouse_id', 'groupby', 'bundles_id', 'qty',
    'rate', 'amount', 'tax', 'tax_amount', 'batch_code',
    'out_qty_details', 'status', 'date', 'username',
];
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
