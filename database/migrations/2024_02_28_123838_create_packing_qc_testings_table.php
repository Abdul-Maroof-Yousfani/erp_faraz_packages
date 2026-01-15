<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingQcTestingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('packing_qc_testings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('packing_id');
            $table->unsignedBigInteger('qc_packing_id');
            $table->unsignedBigInteger('packing_data_id');
            $table->unsignedBigInteger('qc_test_id');
            $table->text('test_result')->nullable()->default('');
            $table->integer('qc_test_status')->default(1);
            $table->string('status');
            $table->string('username');
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
        Schema::connection('mysql2')->dropIfExists('packing_qc_testings');
    }
}
