<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubCategoryIdToProductionRolling extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('production_rolling', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('production_rolling', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable()->after('item_id');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('production_rolling', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('production_rolling', 'sub_category_id')) {
                $table->dropColumn('sub_category_id');
            }
        });
    }
}

