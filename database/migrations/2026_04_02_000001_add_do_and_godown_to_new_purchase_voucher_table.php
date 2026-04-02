<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoAndGodownToNewPurchaseVoucherTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'do_no')) {
                $table->string('do_no', 100)->nullable()->after('slip_no');
            }
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'godown_no')) {
                $table->string('godown_no', 100)->nullable()->after('do_no');
            }
             if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'term_of_del')) {
                $table->string('term_of_del', 255)->nullable()->after('godown_no');
            }
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'destination')) {
                $table->string('destination', 255)->nullable()->after('term_of_del');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'godown_no')) {
                $table->dropColumn('godown_no');
            }
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'do_no')) {
                $table->dropColumn('do_no');
            }
        });
    }
}

