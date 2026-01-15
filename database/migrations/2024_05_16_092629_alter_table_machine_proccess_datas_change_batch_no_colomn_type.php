<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMachineProccessDatasChangeBatchNoColomnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('machine_proccess_datas', function (Blueprint $table) {
            $table->text('batch_no')->change();
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
            $table->text('batch_no')->change();
        });
    }
}
