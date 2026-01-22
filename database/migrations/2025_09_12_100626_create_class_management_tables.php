<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create class_groups table if it doesn't exist
        if (!Schema::hasTable('class_groups')) {
            Schema::create('class_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('section')->nullable();
                $table->string('subject')->nullable();
                $table->string('room_number')->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
                $table->integer('academic_year');
                $table->string('semester')->default('First');
                $table->boolean('is_active')->default(true);
                $table->integer('capacity')->default(30); // Default to 30, not 0
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // Create class_group_student pivot table if it doesn't exist
        if (!Schema::hasTable('class_group_student')) {
            Schema::create('class_group_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['class_group_id', 'student_id']);
            });
        }

        // Remove the old class_id column from students if it exists
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'class_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_group_student');
        Schema::dropIfExists('class_groups');
        
        // Re-add class_id column for rollback
        if (Schema::hasTable('students') && !Schema::hasColumn('students', 'class_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('class_id')->nullable()->after('user_id');
            });
        }
    }
};