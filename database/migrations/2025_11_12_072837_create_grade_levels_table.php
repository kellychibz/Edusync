<?php
// database/migrations/2025_11_11_140001_create_grade_levels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Grade 8, Grade 9, etc.
            $table->integer('level')->unique(); // 8, 9, 10, 11, 12
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade_levels');
    }
};