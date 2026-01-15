<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToPackingshTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->integer('pr_id')->after('so_id');
            $table->integer('pr_data_id')->after('pr_id');
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
            $table->dropColumn('pr_id');
            $table->dropColumn('pr_data_id');
        });
    }
}
