<?php
// database/migrations/2025_11_11_150000_enhance_class_subject_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_subject', function (Blueprint $table) {
            // Add teacher_id to track which teacher teaches this subject in this class
            $table->foreignId('teacher_id')
                  ->after('subject_id')
                  ->nullable()
                  ->constrained('teachers')
                  ->onDelete('set null');
                  
            // Add periods per week
            $table->integer('periods_per_week')->default(5)->after('teacher_id');
            
            // Add whether this is a core subject
            $table->boolean('is_core')->default(true)->after('periods_per_week');
        });
    }

    public function down()
    {
        Schema::table('class_subject', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id', 'periods_per_week', 'is_core']);
        });
    }
};