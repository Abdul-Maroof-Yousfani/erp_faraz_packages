<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendGatePassForMultiSourceAndParty extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass', 'source_ids')) {
                $table->text('source_ids')->nullable()->after('source_id');
            }

            if (!Schema::connection('mysql2')->hasColumn('gate_pass', 'party_id')) {
                $table->unsignedBigInteger('party_id')->nullable()->after('vehicle_contact');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass', 'source_ids')) {
                $table->dropColumn('source_ids');
            }

            if (Schema::connection('mysql2')->hasColumn('gate_pass', 'party_id')) {
                $table->dropColumn('party_id');
            }
        });
    }
}
