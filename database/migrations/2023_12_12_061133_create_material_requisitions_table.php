<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('material_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mr_no');
            $table->date('mr_date');
            $table->integer('production_id');
            $table->integer('so_id');
            $table->integer('work_id');
            $table->integer('finish_good_id');
            $table->double('finish_good_qty');
            $table->string('mr_status');
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
        Schema::Connection('mysql2')->dropIfExists('material_requisitions');
    }
}
