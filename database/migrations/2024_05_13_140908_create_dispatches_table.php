<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('dispatches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_id');
            $table->integer('material_requisition_id');
            $table->integer('production_plan_id');
            $table->integer('dc_id');
            $table->integer('packing_id');
            $table->integer('customer_id');
            $table->integer('item_id')->nullable();
            
            $table->string('dispatch_no');
            $table->date('dispatch_date');
            $table->string('dispatch_location');
            $table->string('transporter_name');
            $table->string('vehicle_type');
            $table->string('vehicle_no');
            $table->tinyInteger('dispatch_status')->default(1);
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
        Schema::dropIfExists('dispatches');
    }
}
