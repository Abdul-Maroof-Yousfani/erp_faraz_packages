<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model{
    protected $table = 'credit_note';
    //protected $fillable = ['code','parent_code','level1','level2','level3','level4','level5','level6','level7','name','status','branch_id','username','date','time','action','trail_id','operational'];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function creditNoteData()
    {
        return $this->hasMany(CreditNoteData::class, 'master_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }
}
