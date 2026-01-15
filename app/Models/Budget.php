<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budget';
    protected $fillable = [
        'amount',
        'date',
        'user',
        'status',
    ];
    protected $primaryKey = 'id';
}
