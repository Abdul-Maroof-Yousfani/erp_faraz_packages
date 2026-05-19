<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateCalByToSalesOrderDataTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('sales_order_data', 'rate_cal_by')) {
                $table->tinyInteger('rate_cal_by')->default(1)->after('qty_lbs');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('sales_order_data', 'rate_cal_by')) {
                $table->dropColumn('rate_cal_by');
            }
        });
    }
}
