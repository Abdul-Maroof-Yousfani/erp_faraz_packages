<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBagQtyToGatePassDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('gate_pass_data', 'bag_qty')) {
                $table->string('bag_qty', 100)->nullable()->after('qty');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('gate_pass_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('gate_pass_data', 'bag_qty')) {
                $table->dropColumn('bag_qty');
            }
        });
    }
}
