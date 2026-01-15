<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model{
    protected $connection = 'mysql2';
    protected $table = 'quotation';
    protected $fillable = [
        'gst_id',
        'pr_id',
        'pr_no',
        'voucher_no',
        'voucher_date',
        'vendor_id',
        'date',
        'status',
        'username',
        'ref_no'
    ];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function quotationDatas()
    {
        return $this->hasMany(Quotation_Data::class, 'master_id','id');
    }
}
