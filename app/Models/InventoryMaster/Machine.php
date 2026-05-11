<?php

namespace App\Models\InventoryMaster;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
	protected $connection = 'mysql2';
    protected $table = 'machine';
    protected $fillable = [
        'name',
        'department_id',
        'asset_id',
        'operator',
        'status',
        'username'
    ];
}
