<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGatePassStatusToSourceTables extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'gate_pass_status')) {
                $table->tinyInteger('gate_pass_status')->default(0)->after('status');
            }
        });

        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('delivery_note', 'gate_pass_status')) {
                $table->tinyInteger('gate_pass_status')->default(0)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'gate_pass_status')) {
                $table->dropColumn('gate_pass_status');
            }
        });

        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('delivery_note', 'gate_pass_status')) {
                $table->dropColumn('gate_pass_status');
            }
        });
    }
}
