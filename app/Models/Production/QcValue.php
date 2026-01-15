<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QcValue extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'qc_values';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['item_id', 'status', 'qc_type'];
    public function qcValuesData()
    {
        return $this->hasMany(QcValueData::class, 'master_id');
    }
}
