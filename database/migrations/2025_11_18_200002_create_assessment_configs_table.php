<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessment_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('ca_percentage')->default(40);
            $table->integer('final_exam_percentage')->default(60);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['class_group_id', 'subject_id', 'academic_year_id'], 'ass_conf_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessment_configs');
    }
};