<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('term_assessment_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade_letter', 2);
            $table->text('comments')->nullable();
            $table->timestamp('graded_at');
            $table->timestamps();
            
            $table->unique(['student_id', 'term_assessment_id'], 'student_result_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_assessment_results');
    }
};