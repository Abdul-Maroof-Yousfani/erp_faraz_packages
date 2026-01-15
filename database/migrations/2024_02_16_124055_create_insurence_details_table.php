<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurenceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('insurence_details', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->string('policy_company')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('cover_note')->nullable();
            $table->string('tolerance')->nullable();
            $table->string('policy_no')->nullable();
            $table->string('remarks')->nullable();
            $table->string('policy_status')->nullable();
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
        Schema::Connection('mysql2')->dropIfExists('insurence_details');
    }
}
