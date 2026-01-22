<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained('class_groups')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('attendance_type_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->text('notes')->nullable();
            $table->boolean('is_excused')->default(false);
            $table->timestamp('submitted_at');
            $table->timestamps();
            
            // Unique constraint with shorter name
            $table->unique(['student_id', 'class_id', 'subject_id', 'attendance_date'], 'att_unique_record');
            
            // Indexes for better performance with shorter names
            $table->index(['attendance_date', 'class_id'], 'att_date_class');
            $table->index(['student_id', 'attendance_date'], 'att_student_date');
            $table->index(['teacher_id', 'attendance_date'], 'att_teacher_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
};