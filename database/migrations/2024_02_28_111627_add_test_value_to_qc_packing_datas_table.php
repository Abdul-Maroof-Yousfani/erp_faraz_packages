<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestValueToQcPackingDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('qc_packing_datas', function (Blueprint $table) {
            $table->text('test_value')->nullable()->after('qa_test_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('qc_packing_datas', function (Blueprint $table) {
            $table->dropColumn('test_value');
        });
    }
}
