<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColomnToDeliveryNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            $table->tinyInteger('delivery_note_status')->default(1)->after('status');
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
            $table->tinyInteger('delivery_note_status')->default(1)->after('status');
        });
    }
}
