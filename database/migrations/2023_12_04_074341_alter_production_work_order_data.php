<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductionWorkOrderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('production_work_order_data', function (Blueprint $table) {
            $table->renameColumn('machine_id', 'sale_order_data_id');
            $table->renameColumn('capacity', 'order_qty');
            $table->string('printing');
            $table->string('special_instruction');
            $table->string('identification_tape');
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
