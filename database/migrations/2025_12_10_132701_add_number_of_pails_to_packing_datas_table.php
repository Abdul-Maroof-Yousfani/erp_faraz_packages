<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNumberOfPailsToPackingDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packing_datas', function (Blueprint $table) {
            $table->integer('number_of_pails')->nullable()->after('primary_packing_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('packing_datas', function (Blueprint $table) {
            $table->dropColumn('number_of_pails');
        });
    }
}
