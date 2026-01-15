<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSaleOrderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('sales_order', function (Blueprint $table) {
            $table->string('sales_tax_group')->nullable();
            $table->string('sales_tax_rate')->nullable();
            $table->renameColumn('sales_tax','total_amount_after_sale_tax');
          

        });
        

        Schema::Connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            $table->string('delivery_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
