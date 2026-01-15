<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestPerformOnToPackingQcTestingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packing_qc_testings', function (Blueprint $table) {
            $table->integer('test_perform_on')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('packing_qc_testings', function (Blueprint $table) {
            $table->dropColumn('test_perform_on');
        });
    }
}
