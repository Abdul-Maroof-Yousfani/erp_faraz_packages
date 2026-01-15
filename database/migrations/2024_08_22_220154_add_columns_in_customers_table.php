<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
            $table->text('postal_address')->nullable();
            $table->string('contact_person_no')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->tinyInteger('atl_status')->nullable();
            $table->tinyInteger('status_us_236g_h')->nullable();
            $table->string('no_of_days')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
            $table->dropColumn('postal_address');
            $table->dropColumn('contact_person_no');
            $table->dropColumn('contact_person_email');
            $table->dropColumn('atl_status');
            $table->dropColumn('status_us_236g_h');
            $table->dropColumn('no_of_days');
            $table->dropColumn('remarks');
            
        });
    }
}
