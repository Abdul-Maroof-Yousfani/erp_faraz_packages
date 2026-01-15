<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovalStatusToMaterialRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('material_requisitions', function (Blueprint $table) {
            $table->integer('approval_status')->default(1);
            $table->integer('production_plan_data_id')->after('production_id')->default(0);
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('material_requisitions', function (Blueprint $table) {
            $table->dropColumn('approval_status');
            $table->dropColumn('production_plan_data_id');
        });
    }
}
