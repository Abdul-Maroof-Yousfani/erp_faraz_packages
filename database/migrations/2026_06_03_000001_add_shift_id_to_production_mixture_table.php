<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiftIdToProductionMixtureTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('production_mixture', function (Blueprint $table) {
            $table->unsignedInteger('shift_id')->nullable()->after('operator_id');
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('production_mixture', function (Blueprint $table) {
            $table->dropColumn('shift_id');
        });
    }
}
