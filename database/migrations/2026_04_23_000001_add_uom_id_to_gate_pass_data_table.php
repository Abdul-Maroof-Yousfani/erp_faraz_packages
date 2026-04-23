<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUomIdToGatePassDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass_data', 'uom_id')) {
                $table->unsignedBigInteger('uom_id')->nullable()->after('party_id');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass_data', 'uom_id')) {
                $table->dropColumn('uom_id');
            }
        });
    }
}
