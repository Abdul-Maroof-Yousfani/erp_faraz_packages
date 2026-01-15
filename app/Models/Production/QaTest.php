<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class QaTest extends Model
{
    const TYPE_PACKAGING = 1;
    const TYPE_GRN = 2;
    
    protected $connection = 'mysql2';
    protected $fillable = [
        'name',
        'operator',
        'status',
        'qc_type',
        'username'
    ];
}
