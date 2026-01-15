<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurtherTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('further_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('percentage', 10, 2); // Adjust precision and scale accordingly
            $table->integer('acc_id');
            $table->double('rate');
            $table->integer('status');
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
        Schema::connection('mysql2')->dropIfExists('further_taxes');
    }
}
