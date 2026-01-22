<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('term_assessment_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('term_assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('grade_task_id')->constrained()->onDelete('cascade');
            $table->integer('weight_percentage');
            $table->timestamps();
            
            $table->unique(['term_assessment_id', 'grade_task_id'], 'term_task_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('term_assessment_tasks');
    }
};