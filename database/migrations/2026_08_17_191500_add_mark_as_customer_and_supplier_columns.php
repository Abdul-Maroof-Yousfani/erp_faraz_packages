<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarkAsCustomerAndSupplierColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql2')->hasColumn('supplier', 'mark_as_customer')) {
            Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
                $table->integer('mark_as_customer')->default(0);
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('customers', 'mark_as_supplier')) {
            Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
                $table->integer('mark_as_supplier')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql2')->hasColumn('supplier', 'mark_as_customer')) {
            Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
                $table->dropColumn('mark_as_customer');
            });
        }

        if (Schema::connection('mysql2')->hasColumn('customers', 'mark_as_supplier')) {
            Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
                $table->dropColumn('mark_as_supplier');
            });
        }
    }
}
