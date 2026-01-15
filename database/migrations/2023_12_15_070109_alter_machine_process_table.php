<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMachineProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('machine_proccesses', function (Blueprint $table) {
            $table->double('finish_good_qty')->after('status');
            $table->integer('finish_good_id')->after('status');
            $table->double('ready_qty')->after('status')->nuallable();
            $table->integer('proccess_status')->after('finish_good_qty')->default('1')->comment('1= Ongoing, 2=Partial Received, 3=completed ');
           
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
