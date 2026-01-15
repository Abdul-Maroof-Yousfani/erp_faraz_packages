<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddtColomnToInsurenceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('insurence_details', function (Blueprint $table) {
            $table->date('cover_note_date')->nullable()->after('cover_note');
            $table->date('policy_date')->nullable()->after('policy_no');
            $table->decimal('policy_amount' , 15 , 3)->nullable()->after('policy_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurence_details', function (Blueprint $table) {
            //
        });
    }
}
