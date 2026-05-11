<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentIdToMachineTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('machine', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('machine', 'department_id')) {
                $table->unsignedInteger('department_id')->nullable()->after('name');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('machine', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('machine', 'department_id')) {
                $table->dropColumn('department_id');
            }
        });
    }
}
