<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation_Data extends Model{
    protected $connection = 'mysql2';
    protected $table = 'quotation_data';
    protected $fillable = [
        "master_id",
        "voucher_no",
        "pr_id",
        "pr_data_id",
        "rate",
        "amount",
        'quotation_status'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function demandData()
    {
        return $this->belongsTo(DemandData::class,'pr_data_id','id','demand_data');
    }

}


