<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWastageToPackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('packings', function (Blueprint $table) {
            $table->decimal('wastage', 10, 2)->nullable()->default(0)->after('total_qty');
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
            $table->dropColumn('wastage');
        });
    }
}
