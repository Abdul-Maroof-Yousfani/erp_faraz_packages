<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnsToLcAndLgAgainstPoDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po_data', function (Blueprint $table) {
            $table->integer('ci_qty')->default(0)->after('total_amount');
            $table->decimal('ci_rate' , 20 , 2)->default(0)->after('ci_qty');
            $table->decimal('ci_total_amount' , 20 , 2)->default(0)->after('ci_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lc_and_lg_against_po_data', function (Blueprint $table) {
            //
        });
    }
}
