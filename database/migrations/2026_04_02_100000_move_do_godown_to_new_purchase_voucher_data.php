<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MoveDoGodownToNewPurchaseVoucherData extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher_data', 'do_no')) {
                $table->string('do_no', 100)->nullable();
            }
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher_data', 'godown_no')) {
                $table->string('godown_no', 100)->nullable();
            }
        });

        if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'do_no')
            || Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'godown_no')) {
            $masters = DB::connection('mysql2')->table('new_purchase_voucher')->select('id', 'do_no', 'godown_no')->get();
            $hasAdditional = Schema::connection('mysql2')->hasColumn('new_purchase_voucher_data', 'additional_exp');

            foreach ($masters as $m) {
                $do = $m->do_no ?? null;
                $go = $m->godown_no ?? null;
                if (($do === null || $do === '') && ($go === null || $go === '')) {
                    continue;
                }

                $q = DB::connection('mysql2')->table('new_purchase_voucher_data')->where('master_id', $m->id);
                if ($hasAdditional) {
                    $q->where('additional_exp', 0);
                }
                $q->update([
                    'do_no' => $do,
                    'godown_no' => $go,
                ]);
            }
        }

        Schema::connection('mysql2')->table('new_purchase_voucher', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'do_no')) {
                $table->dropColumn('do_no');
            }
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'godown_no')) {
                $table->dropColumn('godown_no');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'do_no')) {
                $table->string('do_no', 100)->nullable()->after('slip_no');
            }
            if (!Schema::connection('mysql2')->hasColumn('new_purchase_voucher', 'godown_no')) {
                $table->string('godown_no', 100)->nullable()->after('do_no');
            }
        });

        Schema::connection('mysql2')->table('new_purchase_voucher_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher_data', 'do_no')) {
                $table->dropColumn('do_no');
            }
            if (Schema::connection('mysql2')->hasColumn('new_purchase_voucher_data', 'godown_no')) {
                $table->dropColumn('godown_no');
            }
        });
    }
}
