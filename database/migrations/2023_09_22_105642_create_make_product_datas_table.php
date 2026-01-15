<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMakeProductDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('make_product_datas', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('make_product_id');
            $table->integer('recipe_data_id');
            $table->string('sub_item_name');
            $table->string('uom');
            $table->integer('actual_qty');
            $table->integer('total_qty');
            $table->decimal('rate_per_qty');
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
        Schema::Connection('mysql2')->dropIfExists('make_product_datas');
    }
}
