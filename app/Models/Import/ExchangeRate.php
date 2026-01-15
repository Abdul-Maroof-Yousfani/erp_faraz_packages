<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';
	protected $connection = 'mysql2';
    protected $fillable = ['currency', 'rate_date', 'rate','to_date', 'status', 'username'];

}
