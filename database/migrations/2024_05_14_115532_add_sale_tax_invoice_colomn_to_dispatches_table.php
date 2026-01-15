<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaleTaxInvoiceColomnToDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('dispatches', function (Blueprint $table) {
            $table->tinyInteger('sales_tax_invoice')->default(1)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('dispatches', function (Blueprint $table) {
            $table->tinyInteger('sales_tax_invoice')->default(1)->after('status');
        });
    }
}
