<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUomToGatePassDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass_data', 'uom')) {
                $table->string('uom', 100)->nullable()->after('party_id');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass_data', 'uom')) {
                $table->dropColumn('uom');
            }
        });
    }
}
