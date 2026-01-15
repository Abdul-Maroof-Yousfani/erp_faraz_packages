<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcAndLgAgainstPoDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('lc_and_lg_against_po_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('master_id');
            $table->integer('item_id');
            $table->integer('qty')->default(0);
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('hs_code_amount', 10, 2)->default(0);
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
        Schema::connection('mysql2')->dropIfExists('lc_and_lg_against_po_data');
    }
}
