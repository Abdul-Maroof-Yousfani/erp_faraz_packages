<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalesTaxGroupToSalesOrderDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_order_data', 'sales_tax_group')) {
                $table->integer('sales_tax_group')->default(0)->after('amount');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_order_data', 'sales_tax_group')) {
                $table->dropColumn('sales_tax_group');
            }
        });
    }
}
