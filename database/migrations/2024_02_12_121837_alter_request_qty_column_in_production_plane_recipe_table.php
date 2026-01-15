<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRequestQtyColumnInProductionPlaneRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('production_plane_recipe', function (Blueprint $table) {
            $table->decimal('request_qty', 10, 4)->change(); // Change the parameters as needed
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
            $table->integer('request_qty')->change(); // Revert the column type if needed
        });
    }
}

