<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoColomnToLcAndLgAgainstPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg_against_po', function (Blueprint $table) {
            $table->string('lc_no')->nullable()->after('po_id');
            $table->date('lc_date')->nullable()->after('lc_no');
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
