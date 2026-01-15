<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSaleOrderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            $table->string('thickness')->nullable();
            $table->string('diemetter')->nullable();
            $table->string('printing')->nullable();
            $table->string('special_instruction')->nullable();
            $table->date('delivery_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('sales_order_data', function ($table) {
            $table->dropColumn(['printing']);
            $table->dropColumn(['special_instruction']);
            $table->dropColumn(['delivery_date']);
            
            });
        }
}
