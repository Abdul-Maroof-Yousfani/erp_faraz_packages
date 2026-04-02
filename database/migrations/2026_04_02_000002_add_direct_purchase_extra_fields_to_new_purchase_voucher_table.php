<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectPurchaseExtraFieldsToNewPurchaseVoucherTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher', function (Blueprint $table) {
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
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'destination')) {
                $table->dropColumn('destination');
            }
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'term_of_del')) {
                $table->dropColumn('term_of_del');
            }
        });
    }
}

