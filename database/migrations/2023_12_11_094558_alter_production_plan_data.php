<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductionPlanData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('production_plane_data', function (Blueprint $table) {
            $table->integer('receipe_id');
            $table->date('start_date');  
            $table->date('delivery_date');  
            $table->integer('type');  
        });
        
        Schema::Connection('mysql2')->table('production_plane', function (Blueprint $table) {
            $table->string('sale_order_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
