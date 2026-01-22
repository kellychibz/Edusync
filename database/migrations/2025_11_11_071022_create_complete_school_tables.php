<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. First create teachers table if it doesn't exist
        if (!Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('phone')->nullable();
                $table->string('qualification');
                $table->string('specialization')->nullable();
                $table->integer('years_of_experience')->default(0);
                $table->date('hire_date');
                $table->text('bio')->nullable();
                $table->string('office_location')->nullable();
                $table->string('office_hours')->nullable();
                $table->string('profile_image')->nullable();
                $table->timestamps();
            });
        }

        // 2. Then create class_groups table if it doesn't exist
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
                $table->integer('capacity')->default(30);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Create class_group_student pivot table if it doesn't exist
        if (!Schema::hasTable('class_group_student')) {
            Schema::create('class_group_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['class_group_id', 'student_id']);
            });
        }

        // 4. Fix subject_teacher table if it exists with wrong constraints
        if (Schema::hasTable('subject_teacher')) {
            // Drop and recreate with proper constraints
            Schema::dropIfExists('subject_teacher');
        }
        
        Schema::create('subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['subject_id', 'teacher_id']);
        });

        // 5. Remove old class_id from students if it exists
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'class_id')) {
            Schema::table('students', function (Blueprint $table) {
                // Drop foreign key first
                $table->dropForeign(['class_id']);
                // Then drop the column
                $table->dropColumn('class_id');
            });
        }
    }

    public function down(): void
    {
        // Drop tables in reverse order
        Schema::dropIfExists('subject_teacher');
        Schema::dropIfExists('class_group_student');
        Schema::dropIfExists('class_groups');
        Schema::dropIfExists('teachers');
    }
};