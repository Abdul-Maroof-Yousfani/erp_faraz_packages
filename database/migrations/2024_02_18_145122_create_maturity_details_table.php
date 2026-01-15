<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaturityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('maturity_details', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->Integer('curreny_id')->default(0);
            $table->decimal('bank_doc_amount',15,0)->default(0);
            $table->decimal('rate',15,0)->default(0);
            $table->decimal('pkr',15,0)->default(0);
            $table->string('lot_no')->nullable();
            $table->Integer('days')->default(0);
            $table->date('maturity_date')->nullable();
            $table->longtext('remarks')->nullable(0);
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
        Schema::dropIfExists('maturity_details');
    }
}
