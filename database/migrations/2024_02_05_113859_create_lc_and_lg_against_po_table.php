<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcAndLgAgainstPoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('lc_and_lg_against_po', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('po_id');
            $table->string('buyer_name');
            $table->unsignedBigInteger('buyer_id')->default(0);
            $table->text('applicant_full_address');
            $table->string('beneficiary_name');
            $table->unsignedBigInteger('beneficiary_id')->default(0);
            $table->text('beneficiary_full_address');
            $table->string('advising_bank');
            $table->unsignedBigInteger('advising_bank_id')->default(0);
            $table->string('advising_bank_account_no');
            $table->string('advising_bank_swift_code');
            $table->string('inter_mediary_bank');
            $table->unsignedBigInteger('inter_mediary_bank_id')->default(0);
            $table->double('inter_mediary_bank_account_no');
            $table->string('inter_mediary_bank_swift_code');
            $table->string('Currency');
            $table->unsignedBigInteger('Currency_id')->default(0);
            $table->decimal('amount');
            $table->string('fob');
            $table->string('cfr');
            $table->string('cpt');
            $table->string('sight');
            $table->string('shipment_from');
            $table->string('shipment_to');
            $table->date('latest_shipment_date')->nullable();
            $table->date('expirty_date')->nullable();
            $table->double('days_from');
            $table->date('bl_date')->nullable();
            $table->string('delivery_type');
            $table->string('origin');
            $table->string('hs_code');
            $table->string('insurance');
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
        Schema::connection('mysql2')->dropIfExists('lc_and_lg_against_po');
    }
}
