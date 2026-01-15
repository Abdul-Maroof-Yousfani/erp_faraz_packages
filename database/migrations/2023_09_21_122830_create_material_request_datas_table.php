<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Connection('mysql2')->create('material_request_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('material_request_no', 150);
            $table->date('material_request_date');
            $table->date('required_date');
            $table->integer('sub_item_id');
            $table->integer('uom_id');
            $table->decimal('qty', 15, 3);
            $table->decimal('approx_cost', 15, 3);
            $table->decimal('approx_sub_total', 15, 3);
            $table->text('sub_description');
            $table->integer('material_request_status')->default(1)->comment('1 = Pending, 2 = Approve, 3 = Rejected');
            $table->integer('store_challan_status')->default(1)->comment('1 = Pending, 2 = Issued');
            $table->integer('status');
            $table->date('date');
            $table->string('time', 20);
            $table->string('username', 200);
            $table->integer('user_id');
            $table->string('approve_username', 200);
            $table->string('delete_username', 200);
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
        Schema::Connection('mysql2')->dropIfExists('material_request_datas');
    }
}
