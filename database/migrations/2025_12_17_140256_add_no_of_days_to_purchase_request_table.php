<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoOfDaysToPurchaseRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('purchase_request', function (Blueprint $table) {
            $table->integer('no_of_days')->nullable()->after('terms_of_paym');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('purchase_request', function (Blueprint $table) {
            $table->dropColumn('no_of_days');
        });
    }
}
