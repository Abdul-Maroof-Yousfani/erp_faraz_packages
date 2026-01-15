<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QcGrn extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'qc_grns';
    protected $fillable = [
        'new_pv_id',
        'pr_id',
        'grn_id',
        'purchase_return_id',
        'supplier_id',
        'qc_grn_date',
        'qc_by',
        'status',
        'username',
    ];
}
