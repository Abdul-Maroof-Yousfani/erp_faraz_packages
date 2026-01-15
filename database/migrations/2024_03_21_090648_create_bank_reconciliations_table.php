<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('bank_reconciliations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('account_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->decimal('bank_opening_balance_erp', 16, 2)->nullable()->default(0);
            $table->decimal('bank_closing_balance', 16, 2)->nullable()->default(0);
            $table->decimal('bank_statement_balance', 16, 2)->nullable()->default(0);
            $table->decimal('deposits', 16, 2)->nullable()->default(0);
            $table->decimal('company_book_balance', 16, 2)->nullable()->default(0);
            $table->decimal('outstanding', 16, 2)->nullable()->default(0);
            $table->decimal('difference', 16, 2)->nullable()->default(0);
            $table->string('status');
            $table->string('username');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('bank_reconciliations');
    }
}
