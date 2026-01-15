<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_totals extends Model{
    protected $table = 'invoice_data_totals';
    protected $primaryKey = 'master_id';
    public $timestamps = false;
}
