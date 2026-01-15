<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuotationDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('sale_quotation_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_quotaion_id');
            $table->string('quotation_no');
            $table->string('item_id');
            $table->string('item_description');
            $table->string('qty');
            $table->string('uom_id');
            $table->double('unit_price');
            $table->double('total_amount');
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_quotation_datas');
    }
}
