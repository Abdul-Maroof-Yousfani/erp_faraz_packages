<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->integer('qc_status')->nullable()->default(1);
            $table->integer('total_qty')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->dropColumn('qc_status');
            $table->dropColumn('total_qty');
        });
    }
}
