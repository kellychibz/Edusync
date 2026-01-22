<?php
// database/migrations/2025_11_11_140003_add_grade_level_id_to_class_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->foreignId('grade_level_id')
                  ->after('teacher_id')
                  ->nullable()
                  ->constrained('grade_levels')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->dropForeign(['grade_level_id']);
            $table->dropColumn('grade_level_id');
        });
    }
};