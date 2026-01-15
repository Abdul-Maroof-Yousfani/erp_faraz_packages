<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBlDetailsChangeSomeColomnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('bl_details', function (Blueprint $table) {
            $table->float('lcl')->change();
            $table->float('ft_20')->change();
            $table->float('ft_40')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bl_details', function (Blueprint $table) {
            //
        });
    }
}
