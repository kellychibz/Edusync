<?php
// database/migrations/2025_11_11_140002_add_department_id_to_teachers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('department_id')
                  ->after('user_id')
                  ->nullable()
                  ->constrained('departments')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};