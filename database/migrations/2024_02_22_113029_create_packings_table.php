<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('packings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('so_id');
            $table->integer('material_requisition_id');
            $table->integer('production_plan_id')->nullable();
            $table->string('customer_name');
            $table->integer('customer_id');
            $table->date('packing_date');
            $table->text('deliver_to');
            $table->text('packing_list_no');
            $table->integer('item_id');
            $table->string('item_name');
            $table->integer('status')->default(1);
            $table->string('username')->nullable();
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
        Schema::connection('mysql2')->dropIfExists('packings');
    }
}
