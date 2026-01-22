<?php
// database/migrations/2024_01_01_create_teacher_department_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['teacher_id', 'department_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_department');
    }
};