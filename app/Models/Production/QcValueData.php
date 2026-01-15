<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcValueData extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'qc_values_data';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['master_id', 'test_id', 'standard_value', 'status'];
    public function qcValue()
    {
        return $this->belongsTo(QcValue::class, 'master_id');
    }
}
