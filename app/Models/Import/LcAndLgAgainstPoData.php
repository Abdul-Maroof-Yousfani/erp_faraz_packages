<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class LcAndLgAgainstPoData extends Model
{
    protected $table = 'lc_and_lg_against_po_data';
	protected $connection = 'mysql2';

    protected $fillable = [
        'master_id',
        'item_id',
        'qty',
        'rate',
        'total_amount',
        'hs_code_amount',
        'status',
        'username',
    ];
}
