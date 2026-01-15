<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispatchDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dispatch_datas', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('dispatch_id');
            $table->integer('machine_proccess_data_id');
            $table->integer('item_id');
            $table->tinyInteger('status')->default(1);
            $table->string('username');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_datas');
    }
}
