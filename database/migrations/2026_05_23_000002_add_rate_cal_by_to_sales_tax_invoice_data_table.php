<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice_data', 'rate_cal_by')) {
                $table->tinyInteger('rate_cal_by')->default(2)->after('bag_qty');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice_data', 'rate_cal_by')) {
                $table->dropColumn('rate_cal_by');
            }
        });
    }
};

