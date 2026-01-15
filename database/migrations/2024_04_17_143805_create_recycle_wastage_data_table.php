<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecycleWastageDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('recycle_wastage_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id');
            $table->tinyInteger('item_type')->comment('	1 = recycle material , 2 raw material	');
            $table->integer('item_id');
            $table->integer('warehouse_id')->nullable();
            $table->string('batch_code')->nullable();
            $table->string('ppc')->nullable();
            $table->decimal('qty' , 15 ,3); 
            $table->tinyInteger('status')->default(1); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recycle_wastage_data');
    }
}
