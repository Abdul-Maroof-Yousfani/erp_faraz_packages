<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class LcAndLg extends Model
{
    protected $table = 'lc_and_lg';
	protected $connection = 'mysql2';
    protected $fillable = [
        'type',
        'acc_id',
        'limit',
        'status',
        'username',
    ];
}
