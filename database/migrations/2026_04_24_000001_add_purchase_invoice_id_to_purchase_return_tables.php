<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseInvoiceIdToPurchaseReturnTables extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('purchase_return', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('purchase_return', 'purchase_invoice_id')) {
                $table->unsignedBigInteger('purchase_invoice_id')->nullable()->after('grn_id');
            }
        });

        Schema::connection('mysql2')->table('purchase_return_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('purchase_return_data', 'purchase_invoice_id')) {
                $table->unsignedBigInteger('purchase_invoice_id')->nullable()->after('grn_data_id');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('purchase_return_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('purchase_return_data', 'purchase_invoice_id')) {
                $table->dropColumn('purchase_invoice_id');
            }
        });

        Schema::connection('mysql2')->table('purchase_return', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('purchase_return', 'purchase_invoice_id')) {
                $table->dropColumn('purchase_invoice_id');
            }
        });
    }
}
