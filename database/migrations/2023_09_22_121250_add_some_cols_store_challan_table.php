<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeColsStoreChallanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('store_challan', function (Blueprint $table) {
            $table->string('material_request_no', 255)->after('slip_no');
            $table->date('material_request_date')->after('slip_no');
            $table->bigInteger('warehouse_from_id')->after('slip_no');
            $table->bigInteger('warehouse_to_id')->after('slip_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('store_challan', function (Blueprint $table) {
            $table->dropColumn('material_request_no');
            $table->dropColumn('material_request_date');
            $table->dropColumn('warehouse_from_id');
            $table->dropColumn('warehouse_to_id');
        });
    }
}
