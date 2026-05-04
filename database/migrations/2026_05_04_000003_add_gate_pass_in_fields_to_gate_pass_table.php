<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGatePassInFieldsToGatePassTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass', 'gate_pass_in_description')) {
                $table->text('gate_pass_in_description')->nullable()->after('description');
            }

            if (!Schema::connection('mysql2')->hasColumn('gate_pass', 'gate_pass_in_status')) {
                $table->tinyInteger('gate_pass_in_status')->default(0)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass', 'gate_pass_in_description')) {
                $table->dropColumn('gate_pass_in_description');
            }

            if (Schema::connection('mysql2')->hasColumn('gate_pass', 'gate_pass_in_status')) {
                $table->dropColumn('gate_pass_in_status');
            }
        });
    }
}
