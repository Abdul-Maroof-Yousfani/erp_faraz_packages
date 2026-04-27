<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxSummaryToPurchaseReturnTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('purchase_return', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('purchase_return', 'sales_tax_acc_id')) {
                $table->unsignedBigInteger('sales_tax_acc_id')->default(0)->after('supplier_id');
            }

            if (!Schema::connection('mysql2')->hasColumn('purchase_return', 'before_tax_amount')) {
                $table->decimal('before_tax_amount', 18, 2)->default(0)->after('sales_tax_acc_id');
            }

            if (!Schema::connection('mysql2')->hasColumn('purchase_return', 'sales_tax_amount')) {
                $table->decimal('sales_tax_amount', 18, 2)->default(0)->after('before_tax_amount');
            }

            if (!Schema::connection('mysql2')->hasColumn('purchase_return', 'after_tax_amount')) {
                $table->decimal('after_tax_amount', 18, 2)->default(0)->after('sales_tax_amount');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('purchase_return', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('purchase_return', 'after_tax_amount')) {
                $table->dropColumn('after_tax_amount');
            }

            if (Schema::connection('mysql2')->hasColumn('purchase_return', 'sales_tax_amount')) {
                $table->dropColumn('sales_tax_amount');
            }

            if (Schema::connection('mysql2')->hasColumn('purchase_return', 'before_tax_amount')) {
                $table->dropColumn('before_tax_amount');
            }

            if (Schema::connection('mysql2')->hasColumn('purchase_return', 'sales_tax_acc_id')) {
                $table->dropColumn('sales_tax_acc_id');
            }
        });
    }
}
