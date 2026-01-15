<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
	protected $connection = 'mysql2';

    protected $fillable = [
        'so_id',
        'material_requisition_id',
        'production_plan_id',
        'dc_id',
        'customer_name',
        'customer_id',
        'packing_date',
        'deliver_to',
        'packing_list_no',
        'item_id',
        'item_name',
        'total_qty',
        'qc_status',
        'status',
        'username'
    ];
}
