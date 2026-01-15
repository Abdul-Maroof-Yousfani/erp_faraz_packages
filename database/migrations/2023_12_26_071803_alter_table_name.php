<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->rename('production_plane_issuence', 'production_plane_recipe');

        Schema::Connection('mysql2')->table('production_plane_data', function (Blueprint $table) {
            $table->renameColumn('receipe_id','category_id');
 
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::Connection('mysql2')->rename('production_plane_recipe', 'production_plane_issuence');
        Schema::Connection('mysql2')->table('production_plane_data', function (Blueprint $table) {
            $table->renameColumn('category_id','receipe_id');
 
        });
    }
}
