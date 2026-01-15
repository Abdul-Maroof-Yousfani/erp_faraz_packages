<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class PackingData extends Model
{
	protected $connection = 'mysql2';

     protected $fillable = [
        'packing_id', 'machine_proccess_data_id', 'bundle_no', 'qty', 'number_of_pails', 'status', 'username',
        'primary_packing_item_id', 'secondary_packing_item_id', 'carton_count', 'primary_packing_rate', 'secondary_packing_rate'
    ];
    //
}
