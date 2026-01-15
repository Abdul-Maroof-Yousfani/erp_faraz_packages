<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QcPacking extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'so_id',
        'material_requisition_id',
        'production_plan_id',
        'dc_id',
        'packing_list_id',
        'customer_name',
        'customer_id',
        'qc_packing_date',
        'qc_by',
        'status',
        'username',
    ];
}
