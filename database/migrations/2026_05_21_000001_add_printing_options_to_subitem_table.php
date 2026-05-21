<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrintingOptionsToSubitemTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            if (!Schema::connection('mysql2')->hasColumn('subitem', 'label_print')) {
                $table->string('label_print', 20)->default('non_print')->after('packing_type');
            }

            if (!Schema::connection('mysql2')->hasColumn('subitem', 'gala_cutting')) {
                $table->string('gala_cutting', 10)->default('no')->after('label_print');
            }
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            if (Schema::connection('mysql2')->hasColumn('subitem', 'gala_cutting')) {
                $table->dropColumn('gala_cutting');
            }

            if (Schema::connection('mysql2')->hasColumn('subitem', 'label_print')) {
                $table->dropColumn('label_print');
            }
        });
    }
}
