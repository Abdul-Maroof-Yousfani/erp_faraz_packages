<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDeliveryNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            $table->integer('packing_list_id')->nullable();
            $table->integer('qc_packing_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            $table->dropColumn('packing_list_id');
            $table->dropColumn('qc_packing_id');
        });
    }
}
