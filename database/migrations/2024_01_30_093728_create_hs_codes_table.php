<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHsCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('hs_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hs_code')->required();
            $table->text('description');
            $table->decimal('custom_duty', 15, 2)->default(0);
            $table->decimal('regulatory_duty', 15, 2)->default(0);
            $table->decimal('federal_excise_duty', 15, 2)->default(0);
            $table->decimal('additional_custom_duty', 15, 2)->default(0);
            $table->decimal('sales_tax', 15, 2)->default(0);
            $table->decimal('additional_sales_tax', 15, 2)->default(0);
            $table->decimal('income_tax', 15, 2)->default(0);
            $table->decimal('clearing_expense', 15, 2)->default(0);
            $table->decimal('total_duty_without_taxes', 15, 2)->default(0);
            $table->decimal('total_duty_with_taxes', 15, 2)->default(0);
            $table->text('utilise_under_benefit_of');
            $table->text('applicable_sro_benefit');
            $table->integer('status');
            $table->string('username');
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
        Schema::connection('mysql2')->dropIfExists('hs_codes');
    }
}
