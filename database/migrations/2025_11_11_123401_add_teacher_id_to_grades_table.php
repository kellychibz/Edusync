<?php
// database/migrations/xxxx_add_teacher_id_to_grades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('teacher_id')->after('student_id')->nullable()->constrained('teachers');
            // Also add assignment_name if it doesn't exist
            if (!Schema::hasColumn('grades', 'assignment_name')) {
                $table->string('assignment_name')->after('teacher_id')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
            if (Schema::hasColumn('grades', 'assignment_name')) {
                $table->dropColumn('assignment_name');
            }
        });
    }
};