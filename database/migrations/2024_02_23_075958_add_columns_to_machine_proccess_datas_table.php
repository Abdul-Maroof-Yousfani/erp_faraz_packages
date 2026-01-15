<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMachineProccessDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('machine_proccess_datas', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('operator_id');
            $table->string('shift', 2);
            $table->integer('machine_process_stage')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('machine_proccess_datas', function (Blueprint $table) {
            $table->dropColumn('machine_id');
            $table->dropColumn('operator_id');
            $table->dropColumn('shift');
            $table->dropColumn('machine_process_stage');
        });
    }
}
