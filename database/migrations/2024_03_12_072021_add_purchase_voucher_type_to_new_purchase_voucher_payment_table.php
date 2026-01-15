<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseVoucherTypeToNewPurchaseVoucherPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher_payment', function (Blueprint $table) {
            $table->integer('purchase_voucher_type')->nullable()->comment('1=PI, 2=PO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('new_purchase_voucher_payment', function (Blueprint $table) {
            $table->dropColumn('purchase_voucher_type');
        });
    }
}
