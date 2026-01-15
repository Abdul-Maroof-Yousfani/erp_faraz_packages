<?php

namespace App\Models\InventoryMaster;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $connection = 'mysql2';
    // protected $table = 'machine';
    protected $fillable = [
        'name',
        'operator',
        'status',
        'username'
    ];
}
