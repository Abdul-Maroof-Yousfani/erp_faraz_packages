<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColomnToDispatchDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('dispatch_datas', function (Blueprint $table) {
            $table->integer('qty')->after('item_id');
            $table->integer('rate')->nullable()->after('qty');
            $table->integer('warehouse_id')->nullable()->after('rate');
            $table->integer('batch_code')->nullable()->after('warehouse_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('dispatch_datas', function (Blueprint $table) {
            $table->integer('qty')->after('item_id');
            $table->integer('rate')->nullable()->after('qty');
            $table->integer('warehouse_id')->nullable()->after('rate');
            $table->integer('batch_code')->nullable()->after('warehouse_id');
        });
    }
}
