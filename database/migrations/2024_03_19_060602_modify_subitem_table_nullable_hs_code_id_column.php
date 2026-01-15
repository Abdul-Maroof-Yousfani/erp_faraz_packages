<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySubitemTableNullableHsCodeIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->unsignedBigInteger('hs_code_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->unsignedBigInteger('hs_code_id')->nullable(false)->change();
        });
    }
}
