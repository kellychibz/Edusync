<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('assignment_type'); // quiz, exam, project, homework
            $table->decimal('grade', 5, 2); // supports grades like 95.50
            $table->decimal('max_grade', 5, 2)->default(100); // maximum possible grade
            $table->date('date');
            $table->text('comments')->nullable();
            $table->timestamps();

            // Composite index for better performance
            $table->index(['student_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};