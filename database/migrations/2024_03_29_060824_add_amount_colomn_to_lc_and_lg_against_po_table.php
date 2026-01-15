<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAmountColomnToLcAndLgAgainstPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po', function (Blueprint $table) {
            $table->decimal('pkr_amount', 16, 2)->nullable()->default(0);
            $table->decimal('total_duty', 16, 2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lc_and_lg_against_po', function (Blueprint $table) {
            //
        });
    }
}
