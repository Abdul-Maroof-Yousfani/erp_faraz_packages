<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('material_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('warehouse_id');
            $table->string('material_request_no', 150);
            $table->date('material_request_date');
            $table->date('required_date');
            $table->integer('sub_department_id');
            $table->text('description');
            $table->integer('material_request_status')->default(1)->comment('1 = Pending, 2 = Approve, 3 = Rejected');
            $table->string('additional_remarks', 255);
            $table->integer('status');
            $table->string('username', 200);
            $table->integer('user_id');
            $table->string('approve_username', 200);
            $table->date('approve_date')->nullable()->default(null);
            $table->string('approve_time', 20);
            $table->integer('approve_user_id');
            $table->string('delete_username', 200);
            $table->date('delete_date')->nullable()->default(null);
            $table->string('delete_time', 20);
            $table->integer('delete_user_id');
            $table->integer('store_challan_status')->default(1)->comment('1 = Pending, 2 = Issued');
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
        Schema::Connection('mysql2')->dropIfExists('material_requests');
    }
}
