<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\DriverManager;

class AlterSaleOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('sales_order', function (Blueprint $table) {
            $table->renameColumn('order_no', 'purchase_order_no');
            $table->renameColumn('order_date', 'purchase_order_date');
            $table->renameColumn('other_refrence', 'purchase_order_contract');
            $table->renameColumn('due_date', 'delivery_date');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
