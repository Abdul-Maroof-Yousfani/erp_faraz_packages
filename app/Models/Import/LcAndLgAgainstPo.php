<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class LcAndLgAgainstPo extends Model
{
    protected $table = 'lc_and_lg_against_po';
	protected $connection = 'mysql2';
}
