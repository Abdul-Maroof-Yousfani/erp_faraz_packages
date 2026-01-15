<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSoIdAndSaleOrderIdNullableInMaterialRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('material_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('so_id')->nullable()->change();
            $table->unsignedBigInteger('sale_order_id')->nullable()->change();
            $table->decimal('finish_good_qty', 13, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('material_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('so_id')->nullable(false)->change();
            $table->unsignedBigInteger('sale_order_id')->nullable(false)->change();
            $table->integer('finish_good_qty')->change();
        });
    }
}
