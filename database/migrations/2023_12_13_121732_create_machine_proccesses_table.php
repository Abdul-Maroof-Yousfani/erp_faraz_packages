<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineProccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('machine_proccesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('machine_no');
            $table->integer('machine_id');
            $table->integer('mr_id');
            $table->integer('production_plane_id');
            $table->integer('work_order_id');
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
        Schema::dropIfExists('machine_proccesses');
    }
}
