<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactPersonAndPhoneNoToDeliveryNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            $table->string('contact_person')->nullable();
            $table->string('phone_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('delivery_note', function (Blueprint $table) {
            $table->dropColumn('contact_person');
            $table->dropColumn('phone_no');
        });
    }
}
