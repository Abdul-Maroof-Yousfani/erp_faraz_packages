<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatePassTables extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->create('gate_pass', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('gate_pass_no', 50)->unique();
            $table->tinyInteger('gate_pass_type')->default(3);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('source_no', 100)->nullable();
            $table->date('gate_pass_date');
            $table->string('gate_pass_time', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('vehicle_no', 100)->nullable();
            $table->string('vehicle_type', 100)->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->string('transporter_name', 150)->nullable();
            $table->string('vehicle_contact', 50)->nullable();
            $table->integer('company_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
        });

        Schema::connection('mysql2')->create('gate_pass_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gate_pass_id');
            $table->tinyInteger('source_type')->default(3);
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('source_no', 100)->nullable();
            $table->unsignedBigInteger('source_item_id')->nullable();
            $table->string('item_name', 255)->nullable();
            $table->decimal('qty', 18, 4)->default(0);
            $table->decimal('rate', 18, 4)->default(0);
            $table->decimal('amount', 18, 4)->default(0);
            $table->tinyInteger('is_editable')->default(0);
            $table->integer('company_id')->nullable();
            $table->string('username', 100)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->index(['gate_pass_id']);
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('gate_pass_data');
        Schema::connection('mysql2')->dropIfExists('gate_pass');
    }
}
