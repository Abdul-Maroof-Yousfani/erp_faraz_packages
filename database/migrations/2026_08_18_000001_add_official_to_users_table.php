<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficialToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connections = array_filter(['mysql', 'mysql2'], function($conn) {
            return config("database.connections.{$conn}") !== null;
        });

        foreach ($connections as $conn) {
            if (Schema::connection($conn)->hasTable('users')) {
                if (!Schema::connection($conn)->hasColumn('users', 'official')) {
                    Schema::connection($conn)->table('users', function (Blueprint $table) {
                        $table->string('official', 10)->default('1')->nullable();
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connections = array_filter(['mysql', 'mysql2'], function($conn) {
            return config("database.connections.{$conn}") !== null;
        });

        foreach ($connections as $conn) {
            if (Schema::connection($conn)->hasTable('users')) {
                if (Schema::connection($conn)->hasColumn('users', 'official')) {
                    Schema::connection($conn)->table('users', function (Blueprint $table) {
                        $table->dropColumn('official');
                    });
                }
            }
        }
    }
}
