<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('bl_details', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('lc_and_lg_against_po_id');
            $table->string('bl_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('line')->nullable();
            $table->string('fwd')->nullable();
            $table->string('by_sea')->nullable();
            $table->date('bl_date')->nullable();
            $table->decimal('bl_nbp',15,2)->default(0);
            $table->date('eta')->nullable();
            $table->date('receving_date_factory')->nullable();
            $table->string('receving_no_factory')->nullable();
            $table->decimal('lcl',15,2)->default(0);
            $table->decimal('ft_20',15,2)->default(0);
            $table->decimal('ft_40',15,2)->default(0);
            $table->longtext('container')->nullable();
            $table->longtext('packege')->nullable();
            $table->string('ioco')->nullable();
            $table->string('efs')->nullable();
            $table->decimal('sch',15,2)->default(0);
            $table->string('normal')->nullable();
            $table->decimal('new',15,2)->default(0);
            $table->decimal('gw',15,2)->default(0);
            $table->string('port_of_loading')->default(0);
            $table->integer('shipment_status')->defaul(1)->comment('1=Received at Factory');
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
        Schema::Connection('mysql2')->dropIfExists('bl_details');
    }
}
