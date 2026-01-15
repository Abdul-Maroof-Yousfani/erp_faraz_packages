<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHsCodeIdToSubitems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->unsignedBigInteger('hs_code_id')->default(0)->after('uom2');
            // You can adjust the data type (unsignedBigInteger) and default value as needed
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->dropColumn('hs_code_id');
        });
    }
}
