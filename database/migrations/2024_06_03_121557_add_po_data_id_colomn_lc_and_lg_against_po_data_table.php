<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPoDataIdColomnLcAndLgAgainstPoDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po_data', function (Blueprint $table) {
            $table->integer('po_data_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po_data', function (Blueprint $table) {
            $table->integer('po_data_id')->nullable()->after('id');
        });
    }
}
