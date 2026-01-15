<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentsAndNullableToProductionPlaneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('production_plane', function (Blueprint $table) {
            $table->string('type')->nullable()->comment('1=normal production, 2=general production')->default(1)->change();
            $table->string('order_no')->nullable()->change();
            $table->date('order_date')->nullable()->change();
            $table->string('sale_order_no')->nullable()->change();
            $table->string('customer')->nullable()->change();
            $table->unsignedBigInteger('work_order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('production_plane', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
            $table->string('order_no')->nullable(false)->change();
            $table->date('order_date')->nullable(false)->change();
            $table->string('sale_order_no')->nullable(false)->change();
            $table->string('customer')->nullable(false)->change();
            $table->unsignedBigInteger('work_order_id')->nullable(false)->change();
        });
    }
}
