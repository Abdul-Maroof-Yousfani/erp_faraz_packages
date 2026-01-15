<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProductionPlaneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('production_plane', function (Blueprint $table) {
            $table->decimal('wall_thickness_1', 8, 2)->nullable();
            $table->decimal('wall_thickness_2', 8, 2)->nullable();
            $table->decimal('wall_thickness_3', 8, 2)->nullable();
            $table->decimal('wall_thickness_4', 8, 2)->nullable();
            $table->decimal('pipe_outer', 8, 2)->nullable();
            $table->text('printing_on_pipe')->nullable();
            $table->text('special_instructions')->nullable();
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
            $table->dropColumn('wall_thickness_1');
            $table->dropColumn('wall_thickness_2');
            $table->dropColumn('wall_thickness_3');
            $table->dropColumn('wall_thickness_4');
            $table->dropColumn('pipe_outer');
            $table->dropColumn('printing_on_pipe');
            $table->dropColumn('special_instructions');
        });
    }
}
