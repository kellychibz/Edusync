<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the pivot table if it doesn't exist
        if (!Schema::hasTable('class_group_student')) {
            Schema::create('class_group_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
                $table->foreignId('student_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['class_group_id', 'student_id']);
            });
        }

        // Remove the old class_id column if it exists
        if (Schema::hasColumn('students', 'class_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_group_student');
        
        // Re-add class_id column if needed (for rollback)
        if (!Schema::hasColumn('students', 'class_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('class_id')->nullable()->constrained('class_groups')->onDelete('set null');
            });
        }
    }
};