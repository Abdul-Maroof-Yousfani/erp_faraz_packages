<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurposeToGatePassDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass_data', 'purpose')) {
                $table->string('purpose', 255)->nullable()->after('item_name');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass_data', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
}
