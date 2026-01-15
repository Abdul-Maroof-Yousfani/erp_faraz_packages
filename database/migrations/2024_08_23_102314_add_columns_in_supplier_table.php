<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
            $table->string('registration_no')->nullable();
            $table->string('contact_person_no')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('product_services_provided')->nullable();
            $table->string('account_representative_name')->nullable();
            $table->string('account_representative_no')->nullable();
            $table->string('account_representative_email')->nullable();
            $table->string('no_of_days')->nullable();
            $table->string('account_title')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ibn')->nullable();
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
            $table->dropColumn('registration_no');
            $table->dropColumn('contact_person_no');
            $table->dropColumn('contact_person_email');
            $table->dropColumn('product_services_provided');
            $table->dropColumn('account_representative_name');
            $table->dropColumn('account_representative_no');
            $table->dropColumn('account_representative_email');
            $table->dropColumn('no_of_days');
            $table->dropColumn('account_title');
            $table->dropColumn('account_no');
            $table->dropColumn('ibn');
           
            
        });
    }
}
