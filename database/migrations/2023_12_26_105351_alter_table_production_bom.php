<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductionBom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->table('production_bom', function (Blueprint $table) {   
            $table->integer('category_id')->after('status')->nullable()->comment('qty in Meter');   
            $table->string('required_qty')->after('status')->nullable()->comment('qty in KG');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
