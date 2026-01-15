<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdToProductionPlaneRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('production_plane_recipe', function (Blueprint $table) {
            $table->integer('item_id')->nullable()->after('bom_data_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('production_plane_recipe', function (Blueprint $table) {
            $table->dropColumn('item_id');
        });
    }
}
