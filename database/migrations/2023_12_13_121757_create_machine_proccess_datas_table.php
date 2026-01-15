<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineProccessDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('machine_proccess_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_no');
            $table->integer('machine_proccess_id');
            $table->integer('mr_data_id');
            $table->string('request_qty');
            $table->integer('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_proccess_datas');
    }
}
