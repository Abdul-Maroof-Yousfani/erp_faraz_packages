<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBiltyNoToSalesTaxInvoiceTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'bilty_no')) {
                $table->string('bilty_no')->nullable()->after('despacth_document_no');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('sales_tax_invoice', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_tax_invoice', 'bilty_no')) {
                $table->dropColumn('bilty_no');
            }
        });
    }
}
