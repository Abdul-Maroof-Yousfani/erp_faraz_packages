<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('shipping_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->string('bl_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('line')->nullable();
            $table->string('fwd')->nullable(0);
            $table->decimal('do_charges',15,2)->default(0);
            $table->decimal('lolo',15,2)->default(0);
            $table->decimal('port_charges',15,2)->default(0);
            $table->decimal('actual_port_charges',15,2)->default(0);
            $table->decimal('port_charges_balance',15,2)->default(0);
            $table->decimal('security_deposit',15,2)->default(0);
            $table->decimal('amount',15,2)->default(0);
            $table->decimal('deduction',15,2)->default(0);
            $table->decimal('refund_amount',15,2)->default(0);
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->longtext('remarks')->nullable();
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
        Schema::dropIfExists('shipping_expenses');
    }
}
