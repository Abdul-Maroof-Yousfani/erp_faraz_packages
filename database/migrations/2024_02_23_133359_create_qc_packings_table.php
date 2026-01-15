<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcPackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('qc_packings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('so_id');
            $table->unsignedBigInteger('material_requisition_id');
            $table->unsignedBigInteger('production_plan_id');
            $table->unsignedBigInteger('packing_list_id');
            $table->text('customer_name');
            $table->unsignedBigInteger('customer_id');
            $table->date('qc_packing_date');
            $table->text('qc_by');
            $table->integer('status')->nullable();
            $table->text('username')->nullable();
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
        Schema::connection('mysql2')->dropIfExists('qc_packings');
    }
}
