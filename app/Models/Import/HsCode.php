<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class HsCode extends Model
{
    protected $table = 'hs_codes';
	protected $connection = 'mysql2';
    protected $fillable = [
        'hs_code',
        'description',
        'custom_duty',
        'regulatory_duty',
        'federal_excise_duty',
        'additional_custom_duty',
        'sales_tax',
        'additional_sales_tax',
        'income_tax',
        'clearing_expense',
        'total_duty_without_taxes',
        'total_duty_with_taxes',
        'utilise_under_benefit_of',
        'applicable_sro_benefit',
        'status',
        'username',
    ];
}
