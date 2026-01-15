<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankReconciliationData extends Model
{
    protected $connection = 'mysql2';
    protected $fillable = [
        'bank_reconciliation_id',
        'account_id',
        'voucher_no',
        'voucher_type',
        'voucher_date',
        'amount_type',
        'debit_amount',
        'credit_amount',
        'detail',
        'PageTitle',
        'check_type',
        'status',
        'username',
    ];
}
