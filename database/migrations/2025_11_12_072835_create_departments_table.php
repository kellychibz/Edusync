<?php
// database/migrations/2025_11_11_140000_create_departments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Science, Mathematics, English, etc.
            $table->string('code')->unique(); // SCI, MATH, ENG
            $table->text('description')->nullable();
            $table->foreignId('head_teacher_id')->nullable()->constrained('teachers');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};