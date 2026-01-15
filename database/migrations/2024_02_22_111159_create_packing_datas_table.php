<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('packing_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('packing_id');
            $table->unsignedBigInteger('machine_proccess_data_id');
            $table->text('bundle_no');
            $table->decimal('qty');
            $table->integer('status');
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
        Schema::connection('mysql2')->dropIfExists('packing_datas');
    }
}
