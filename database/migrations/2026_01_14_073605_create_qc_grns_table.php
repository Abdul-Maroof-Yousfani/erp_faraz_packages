<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcGrnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('qc_grns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('new_pv_id');
            $table->unsignedBigInteger('pr_id');
            $table->unsignedBigInteger('grn_id');
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->date('qc_grn_date');
            $table->text('qc_by');
            $table->integer('status')->nullable();
            $table->text('username')->nullable();
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
        Schema::dropIfExists('qc_grns');
    }
}
