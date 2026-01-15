<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaistageDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('wastage_data', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('master_id');
            $table->integer('item_id');
            $table->decimal('qty',15,2);
            $table->string('ppc')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waistage_data');
    }
}
