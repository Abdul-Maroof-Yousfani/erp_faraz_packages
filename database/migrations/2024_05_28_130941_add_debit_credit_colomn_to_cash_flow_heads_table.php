<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDebitCreditColomnToCashFlowHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('cash_flow_heads', function (Blueprint $table) {
            $table->tinyInteger('debit_credit')->default(0)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('cash_flow_heads', function (Blueprint $table) {
            $table->tinyInteger('debit_credit')->default(0)->after('name');
        });
    }
}
