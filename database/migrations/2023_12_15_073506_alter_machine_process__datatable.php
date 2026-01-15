<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMachineProcessDatatable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('machine_proccess_datas', function (Blueprint $table) {
            $table->integer('finish_good_id')->after('status')->nullable();   
            $table->string('color_line')->after('status')->nullable();   
            $table->string('remarks')->after('color_line')->nullable();   
            $table->string('batch_no')->nullable();
            $table->date('recieved_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
