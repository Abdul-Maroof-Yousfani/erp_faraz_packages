<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currency');
            $table->date('rate_date');
            $table->date('to_date')->nullable(); // Allow NULL values
            $table->integer('rate');
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
        Schema::Connection('mysql2')->dropIfExists('exchange_rates');
    }
}
