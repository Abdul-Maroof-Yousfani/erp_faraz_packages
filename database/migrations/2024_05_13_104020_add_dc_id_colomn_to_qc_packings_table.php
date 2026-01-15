<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDcIdColomnToQcPackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('qc_packings', function (Blueprint $table) {
            $table->integer('dc_id')->after('production_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qc_packings', function (Blueprint $table) {
            $table->integer('dc_id')->after('production_plan_id');
        });
    }
}
