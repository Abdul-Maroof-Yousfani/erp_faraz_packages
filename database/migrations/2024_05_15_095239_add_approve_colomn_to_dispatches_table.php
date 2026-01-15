<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproveColomnToDispatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('dispatches', function (Blueprint $table) {
            $table->tinyInteger('approve_username')->nullable()->after('dispatch_status');
            $table->tinyInteger('approve_date')->nullable()->after('approve_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('dispatches', function (Blueprint $table) {
            $table->tinyInteger('approve_username')->nullable()->after('dispatch_status');
            $table->tinyInteger('approve_date')->nullable()->after('approve_username');
        });
    }
}
