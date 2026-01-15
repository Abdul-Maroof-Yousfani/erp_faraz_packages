<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInDemandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('demand', function (Blueprint $table) {
            $table->integer('material_request_id')->nullable();
            $table->string('material_request_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demand', function (Blueprint $table) {
            $table->dropColumn('material_request_id');
            $table->dropColumn('material_request_no');
        });
    }
}
