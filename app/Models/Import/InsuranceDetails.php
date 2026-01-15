<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class InsuranceDetails extends Model
{
    //
    protected $connection = 'mysql2';
    protected $table = 'insurence_details';
    protected $guarded = [];
}
