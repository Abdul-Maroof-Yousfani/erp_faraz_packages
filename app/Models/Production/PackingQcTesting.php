<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class PackingQcTesting extends Model
{
	protected $connection = 'mysql2';
    protected $fillable = [
        'packing_id',
        'qc_packing_id',
        'packing_data_id',
        'qc_test_id',
        'test_result',
        'qc_test_status',
        'status', 
        'username',
        'test_perform_on',
    ];
}
