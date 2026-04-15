<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalkinColumnsToSalesTaxInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'walkin_customer')) {
                $table->tinyInteger('walkin_customer')->default(0)->after('buyers_id');
            }
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'walkin_customer_name')) {
                $table->string('walkin_customer_name')->nullable()->after('walkin_customer');
            }
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'customer_type')) {
                $table->string('customer_type')->default('customer')->after('walkin_customer_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'customer_type')) {
                $table->dropColumn('customer_type');
            }
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'walkin_customer_name')) {
                $table->dropColumn('walkin_customer_name');
            }
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'walkin_customer')) {
                $table->dropColumn('walkin_customer');
            }
        });
    }
}
