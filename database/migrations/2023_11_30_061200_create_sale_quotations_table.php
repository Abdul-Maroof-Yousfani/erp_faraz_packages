<?php

use Doctrine\DBAL\Schema\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('sale_quotations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('quotation_no');
            $table->string('quotation_date');
            $table->string('q_valid_up_to');
            $table->string('revision_no');
            $table->integer('customer_id');
            $table->integer('currency_id');
            $table->string('inquiry_reference_date');
            $table->string('exchange_rate');
            $table->string('sales_poal');
            $table->string('type');
            $table->string('subject_line');
            $table->string('storage_dimension');
            $table->string('sale_tax_group');
            $table->string('mode_of_delivery');
            $table->string('delivery_term');
            $table->string('terms_condition');
            $table->string('created_by');
            $table->string('designation');
            $table->string('company_name');
            $table->double('grand_total_amount');
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
        Schema::dropIfExists('sale_quotations');
    }
}
