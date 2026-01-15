<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLcAndLgChangeSomeColomnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('lc_and_lg', function (Blueprint $table) {
            $table->decimal('limit' , 20 ,2)->change();
            $table->decimal('limit_utilize' , 20 ,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lc_and_lg', function (Blueprint $table) {
            //
        });
    }
}
