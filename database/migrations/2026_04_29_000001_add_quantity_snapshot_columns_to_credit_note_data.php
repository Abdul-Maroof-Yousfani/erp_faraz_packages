<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantitySnapshotColumnsToCreditNoteData extends Migration
{
    public function up()
    {
        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'invoice_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('invoice_qty', 10, 2)->nullable()->after('qty');
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'previous_return_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('previous_return_qty', 10, 2)->nullable()->after('invoice_qty');
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'returnable_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('returnable_qty', 10, 2)->nullable()->after('previous_return_qty');
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'current_return_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('current_return_qty', 10, 2)->nullable()->after('returnable_qty');
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'balance_after_return_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('balance_after_return_qty', 10, 2)->nullable()->after('current_return_qty');
            });
        }

        if (!Schema::connection('mysql2')->hasColumn('credit_note_data', 'bag_qty')) {
            Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
                $table->decimal('bag_qty', 10, 2)->nullable()->after('balance_after_return_qty');
            });
        }
    }

    public function down()
    {
        Schema::connection('mysql2')->table('credit_note_data', function (Blueprint $table) {
            foreach (['bag_qty', 'balance_after_return_qty', 'current_return_qty', 'returnable_qty', 'previous_return_qty', 'invoice_qty'] as $column) {
                if (Schema::connection('mysql2')->hasColumn('credit_note_data', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
