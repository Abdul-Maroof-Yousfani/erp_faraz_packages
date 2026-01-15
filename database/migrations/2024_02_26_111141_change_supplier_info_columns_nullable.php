<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSupplierInfoColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('supplier_info', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->change();
            $table->string('contact_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('supplier_info', function (Blueprint $table) {
            $table->string('contact_person')->nullable(false)->change();
            $table->string('contact_no')->nullable(false)->change();
        });
    }
}
