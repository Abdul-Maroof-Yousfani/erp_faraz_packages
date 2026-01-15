<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcGrnDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('qc_grn_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('qc_grn_id');
            $table->unsignedBigInteger('qa_test_id');
            $table->text('standard_value')->nullable();
            $table->text('test_value')->nullable();
            $table->text('test_status')->nullable();
            $table->text('test_type')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('qc_grn_datas');
    }
}
