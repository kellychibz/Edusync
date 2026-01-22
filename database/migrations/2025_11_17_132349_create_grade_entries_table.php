<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('grade_tasks')->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('comments')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            
            $table->unique(['task_id', 'student_id']);
            $table->index(['student_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_entries');
    }
};