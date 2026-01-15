<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOrder extends Model{
    protected $table = 'job_order';
    protected $fillable = ['date_ordered','approval_date','due_date','completion','jod_order_no','invoice_no','invoice_date','ordered_by','customer_name',
        'customer_address','customer_job','job_description','job_location','address','contact_person','contact_no','status','username','date','installed','prepared','checked','approved'];
    protected $primaryKey = 'job_order_id';
    public $timestamps = false;
}
