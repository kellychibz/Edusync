<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove the incorrect foreign key
        Schema::table('students', function (Blueprint $table) {
            // First, we need to remove the existing foreign key
            $table->dropForeign(['class_id']);
        });

        // Add the correct foreign key
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('class_id')
                  ->references('id')
                  ->on('class_groups')  // Correct table name
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // For rollback, remove the correct foreign key and add back the old one
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreign('class_id')
                  ->references('id')
                  ->on('classes')  // Old incorrect table name
                  ->onDelete('set null');
        });
    }
};