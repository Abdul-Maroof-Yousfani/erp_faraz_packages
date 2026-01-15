<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('recycle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->decimal('qty',15,2);
            $table->decimal('amount',15,2);
            $table->date('recycle_date');
            $table->string('username');
            $table->tinyInteger('status')->default(1);
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recycle');
    }
}
