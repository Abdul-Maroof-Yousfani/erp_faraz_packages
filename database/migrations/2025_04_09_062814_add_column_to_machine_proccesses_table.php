<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToMachineProccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('machine_proccesses', function (Blueprint $table) {
            $table->decimal('rate')->after('finish_good_qty')->Default(0);
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
            $table->dropColumn('rate');

        });
    }
}
