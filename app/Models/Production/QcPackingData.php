<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QcPackingData extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'qc_packing_id',
        'qa_test_id',
        'test_value',
        'test_type',
        'test_status',
        'remarks',
        'status',
        'username',
    ];
}
