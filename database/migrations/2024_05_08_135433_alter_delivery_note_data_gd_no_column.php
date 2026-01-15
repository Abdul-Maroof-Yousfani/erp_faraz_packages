<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDeliveryNoteDataGdNoColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('delivery_note_data', function (Blueprint $table) {
            // Change the column type to text
            $table->text('gd_no')->change();
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->table('delivery_note_data', function (Blueprint $table) {
            // If you need to revert the change, define the down() method accordingly
            // For example, you can change the column back to its original type
            $table->string('gd_no', 50)->change();
        });
    }
}
