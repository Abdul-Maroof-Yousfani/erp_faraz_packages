<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentIdToOperatorsTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('operators', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('operators', 'department_id')) {
                $table->unsignedInteger('department_id')->nullable()->after('name');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('operators', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('operators', 'department_id')) {
                $table->dropColumn('department_id');
            }
        });
    }
}
