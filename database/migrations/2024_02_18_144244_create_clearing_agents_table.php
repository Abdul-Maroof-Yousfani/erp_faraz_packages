<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearingAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('clearing_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->string('clearing_agent_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('bill_no')->nullable();
            $table->date('bill_date')->nullable();
            $table->decimal('amount',15,0)->default(0);
            $table->decimal('deduction',15,0)->default(0);
            $table->decimal('paid_amount',15,0)->default(0);
            $table->longtext('remarks')->nullable();
            $table->Integer('shipment_clearing_days')->default(0);
            $table->string('transit_time')->nullable();
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
        Schema::dropIfExists('clearing_agents');
    }
}
