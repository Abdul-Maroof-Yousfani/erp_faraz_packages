<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductionWastageForOrderProcess extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('wastage', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('wastage', 'production_order_id')) {
                $table->unsignedInteger('production_order_id')->nullable()->after('id');
            }

            if (!Schema::connection('mysql2')->hasColumn('wastage', 'process')) {
                $table->string('process', 100)->nullable()->after('type');
            }

            if (!Schema::connection('mysql2')->hasColumn('wastage', 'remarks')) {
                $table->string('remarks', 255)->nullable()->after('qty');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('wastage', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('wastage', 'production_order_id')) {
                $table->dropColumn('production_order_id');
            }

            if (Schema::connection('mysql2')->hasColumn('wastage', 'process')) {
                $table->dropColumn('process');
            }

            if (Schema::connection('mysql2')->hasColumn('wastage', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
}
