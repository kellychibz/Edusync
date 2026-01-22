<?php
// database/migrations/2025_11_11_140004_add_department_id_to_subjects_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('department_id')
                  ->after('id')
                  ->nullable()
                  ->constrained('departments')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};