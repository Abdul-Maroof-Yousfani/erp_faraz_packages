<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGDDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('g_d_details', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->string('gd_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->date('date')->nullable();
            $table->decimal('gd_rate',15,2)->default(0);
            $table->longtext('description')->nullable();
            $table->decimal('gd_new',15,2)->default(0);
            $table->decimal('gd_gw',15,2)->default(0);
            $table->Integer('uom')->default(0);
            $table->string('hs_code')->nullable();
            $table->decimal('gd_cfr_value',15,2)->default(0);
            $table->decimal('assessed_value',15,2)->default(0);
            $table->decimal('custome_duty_percent',15,2)->default(0);
            $table->decimal('custome_duty_amount',15,2)->default(0);
            $table->decimal('acd_percent',15,2)->default(0);
            $table->decimal('acd_amount',15,2)->default(0);
            $table->decimal('rd_percent',15,2)->default(0);
            $table->decimal('rd_amount',15,2)->default(0);
            $table->decimal('fed_percent',15,2)->default(0);
            $table->decimal('fed_amount',15,2)->default(0);
            $table->decimal('st_percent',15,2)->default(0);
            $table->decimal('st_amount',15,2)->default(0);
            $table->decimal('ast_percent',15,2)->default(0);
            $table->decimal('ast_amount',15,2)->default(0);
            $table->decimal('it_percent',15,2)->default(0);
            $table->decimal('it_amount',15,2)->default(0);
            $table->decimal('eto_percent',15,2)->default(0);
            $table->decimal('eto_amount',15,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('g_d_details');
    }
}
