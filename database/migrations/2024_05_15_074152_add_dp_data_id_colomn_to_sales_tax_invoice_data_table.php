<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDpDataIdColomnToSalesTaxInvoiceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            $table->tinyInteger('dp_data_ids')->default(0)->after('dn_data_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            $table->tinyInteger('dp_data_ids')->default(0)->after('dn_data_ids');
        });
    }
}
