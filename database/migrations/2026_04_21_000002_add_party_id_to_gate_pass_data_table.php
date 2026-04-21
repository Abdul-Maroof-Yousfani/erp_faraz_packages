<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartyIdToGatePassDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass_data', 'party_id')) {
                $table->unsignedBigInteger('party_id')->nullable()->after('source_no');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass_data', 'party_id')) {
                $table->dropColumn('party_id');
            }
        });
    }
}
