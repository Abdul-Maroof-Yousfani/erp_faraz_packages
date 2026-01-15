<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequisitionDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('material_requisition_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mr_no');
            $table->integer('mr_id');
            $table->integer('production_data_id');
            $table->integer('receipe_id');
            $table->integer('raw_item_id');
            $table->double('request_qty');
            $table->double('issuance_qty')->nullable();
            $table->date('issuance_date')->nullable();
            $table->integer('status')->default('1');
            $table->integer('mr_status')->default('0')->comment('0 = pandding , 1 = issueing');
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
        Schema::Connection('mysql2')->dropIfExists('material_requisition_datas');
    }
}
