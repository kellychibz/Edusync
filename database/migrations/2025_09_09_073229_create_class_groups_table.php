<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Grade 10-A", "Science 101"
            $table->string('section')->nullable(); // e.g., "A", "B", "Morning"
            $table->string('subject')->nullable(); // Optional: specific subject
            $table->string('room_number')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Homeroom teacher
            $table->integer('academic_year'); // e.g., 2024, 2025
            $table->string('semester')->default('First'); // First, Second, Summer
            $table->boolean('is_active')->default(true);
            $table->integer('capacity')->default(30);
            $table->text('description')->nullable();
            $table->timestamps();
          
        });

        // Pivot table for students in class groups (Many-to-Many)
        Schema::create('class_group_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['class_group_id', 'student_id']); // Prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_group_student');
        Schema::dropIfExists('class_groups');
    }
};