<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankReconciliationDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('bank_reconciliation_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('bank_reconciliation_id');
            $table->unsignedBigInteger('account_id');
            $table->string('voucher_no');
            $table->integer('voucher_type');
            $table->date('voucher_date');
            $table->string('amount_type');
            $table->decimal('debit_amount', 16, 2)->nullable()->default(0);
            $table->decimal('credit_amount', 16, 2)->nullable()->default(0);
            $table->text('detail')->nullable();
            $table->text('PageTitle')->nullable();
            $table->integer('check_type')->nullable()->default(0)->comment('0 = no , 1 = yes , it will show in list when value is 1');
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
        Schema::connection('mysql2')->dropIfExists('bank_reconciliation_datas');
    }
}
