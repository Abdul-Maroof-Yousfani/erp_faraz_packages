<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QcGrnData extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'qc_grn_datas';
    protected $fillable = [
        'qc_grn_id',
        'qa_test_id',
        'standard_value',
        'test_value',
        'test_status',
        'test_type',
        'remarks',
        'status',
        'username',
    ];
}
