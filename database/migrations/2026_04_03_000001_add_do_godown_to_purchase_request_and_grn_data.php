<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoGodownToPurchaseRequestAndGrnData extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('purchase_request_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('purchase_request_data', 'do_no')) {
                $table->string('do_no', 100)->nullable();
            }
            if (!Schema::connection('mysql2')->hasColumn('purchase_request_data', 'godown_no')) {
                $table->string('godown_no', 100)->nullable();
            }
        });

        Schema::connection('mysql2')->table('grn_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('grn_data', 'do_no')) {
                $table->string('do_no', 100)->nullable();
            }
            if (!Schema::connection('mysql2')->hasColumn('grn_data', 'godown_no')) {
                $table->string('godown_no', 100)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('grn_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('grn_data', 'do_no')) {
                $table->dropColumn('do_no');
            }
            if (Schema::connection('mysql2')->hasColumn('grn_data', 'godown_no')) {
                $table->dropColumn('godown_no');
            }
        });

        Schema::connection('mysql2')->table('purchase_request_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('purchase_request_data', 'do_no')) {
                $table->dropColumn('do_no');
            }
            if (Schema::connection('mysql2')->hasColumn('purchase_request_data', 'godown_no')) {
                $table->dropColumn('godown_no');
            }
        });
    }
}
