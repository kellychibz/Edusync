<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add an enum column for 'admin', 'teacher', 'student'
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the column if the migration is rolled back
            $table->dropColumn('role');
        });
    }
};