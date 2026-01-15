<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMachineProccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('machine_proccesses', function (Blueprint $table) {
            // Change column types
            $table->string('machine_no')->nullable()->change();
            $table->integer('machine_id')->nullable()->change();
            $table->integer('shift')->nullable()->change();

            // Add new column
            $table->date('machine_process_date');
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
            // Revert column changes
            $table->string('machine_no')->change();
            $table->integer('machine_id')->change();
            $table->integer('shift')->change();

            // Drop the added column
            $table->dropColumn('machine_process_date');
        });
    }
}
