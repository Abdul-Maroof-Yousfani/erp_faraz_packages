<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMakeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('make_products', function (Blueprint $table) {

            $table->increments('id');
            $table->string('mp_no');
            $table->integer('recipe_id');
            $table->string('sub_item_name');
            $table->integer('quantity');
            $table->decimal('electricity_expense')->nullable();
            $table->decimal('labour_expense')->nullable();
            $table->decimal('expense')->nullable();
            $table->decimal('average_cost');
            $table->string('created_by');
            $table->integer('status')->default('1');
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
        Schema::Connection('mysql2')->dropIfExists('make_products');
    }
}
