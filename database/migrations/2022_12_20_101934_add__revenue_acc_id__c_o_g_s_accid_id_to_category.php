<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRevenueAccIdCOGSAccidIdToCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::connection('mysql2')->table('category', function ($table) {
            $table->integer('revenue_acc_id')->default(0)->after('id');
            $table->integer('cogs_acc_id')->default(0)->after('revenue_acc_id');
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
