<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentForToNewPvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('new_pv', function (Blueprint $table) {
            $table->integer('payment_for')->nullable()->comment('1=Advance, 2=Invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('new_pv', function (Blueprint $table) {
            $table->dropColumn('payment_for');
        });
    }
}
