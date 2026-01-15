<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIntoLGAgainstPO extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po', function (Blueprint $table) {
            $table->string('pi_no')->nullable();
            $table->string('refrence_no')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('applicant_bank')->nullable();
            $table->boolean('partial_shipment')->default(0);
            $table->boolean('transhipment')->default(0);
            $table->longtext('description')->nullable();
            $table->longtext('sub_description')->nullable();

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
