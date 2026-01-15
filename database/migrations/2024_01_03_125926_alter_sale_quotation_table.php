<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSaleQuotationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('sale_quotations', function (Blueprint $table) {
            $table->string('customer_type');
            $table->string('sales_tax_rate')->nullable();
            $table->double('total_amount_after_sale_tax')->nullable();

        });
        

        Schema::Connection('mysql2')->table('sale_quotation_datas', function (Blueprint $table) {
            $table->string('delivery_type')->nullable();
            $table->double('amount_after_sale_tax')->nullable();
            $table->string('sales_tax_rate')->nullable();

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
