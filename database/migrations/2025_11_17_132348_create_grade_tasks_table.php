<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['assignment', 'quiz', 'test', 'exam', 'project', 'homework', 'participation']);
            $table->decimal('max_score', 5, 2)->default(100);
            $table->date('due_date')->nullable();
            $table->timestamps();
            
            $table->index(['class_group_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_tasks');
    }
};