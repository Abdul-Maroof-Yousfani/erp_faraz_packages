<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcPackingDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('qc_packing_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('qc_packing_id');
            $table->unsignedBigInteger('qa_test_id');
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
        Schema::connection('mysql2')->dropIfExists('qc_packing_datas');
    }
}
