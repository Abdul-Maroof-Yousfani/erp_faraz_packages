<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecycleWastageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('recycle_wastage', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->comment('1 wastaage , 2 recycle');
            $table->integer('item_id');
            $table->integer('warehouse_id');
            $table->string('batch_code');
            $table->decimal('qty' , 15 ,3);
            $table->tinyInteger('approval_status')->default(1);
            $table->date('recycle_wastage_date');
            $table->string('username');
            $table->string('approval_username')->nullable(); 
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('recycle_wastage');
    }
}
