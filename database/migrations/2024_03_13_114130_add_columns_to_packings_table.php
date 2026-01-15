<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->integer('production_attached')->default(0)->comment('0 = not, 1 = yes');
            $table->integer('attached_pp_id')->default(0);
            $table->integer('attached_material_requisition_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->dropColumn('production_attached');
            $table->dropColumn('attached_pp_id');
            $table->dropColumn('attached_material_requisition_id');
        });
    }
}
