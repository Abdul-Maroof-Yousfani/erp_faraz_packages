<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBagQtyToSalesTaxInvoiceDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice_data', 'bag_qty')) {
                $table->string('bag_qty', 100)->nullable()->after('qty');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice_data', 'bag_qty')) {
                $table->dropColumn('bag_qty');
            }
        });
    }
}
