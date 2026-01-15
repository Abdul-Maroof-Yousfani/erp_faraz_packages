<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMachineMachineProccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('machine_proccesses', function (Blueprint $table) {
            $table->integer('so_id')->nullable();
            $table->integer('shift')->nullable();
            $table->string('serial_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('machine_proccesses', function (Blueprint $table) {
            $table->dropColumn('so_id');
            $table->dropColumn('shift');
            $table->dropColumn('serial_no');
        });
    }
}
