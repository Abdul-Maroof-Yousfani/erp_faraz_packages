<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecycleDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('recycle_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id');
            $table->integer('item_type')->comment('1 = recycle material , 2 raw material');
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->decimal('qty',15,2);
            $table->decimal('amount',15,2);
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
        Schema::dropIfExists('recycle_data');
    }
}
