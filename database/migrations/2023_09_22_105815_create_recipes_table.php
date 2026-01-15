<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('recipes', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('item_id');
            $table->integer('status');
            $table->string('created_by');
            $table->string('discription');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
    
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::Connection('mysql2')->dropIfExists('recipes');
    }
}
