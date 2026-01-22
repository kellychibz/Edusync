<?php
// database/migrations/2025_11_12_100000_create_streams_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Science', 'Arts', 'Technical'
            $table->string('code'); // e.g., 'SCI', 'ART', 'TEC'
            $table->text('description')->nullable();
            $table->json('core_subjects')->nullable(); // Default subjects for this stream
            $table->json('optional_subjects')->nullable(); // Optional subjects
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add stream_id to class_groups table
        Schema::table('class_groups', function (Blueprint $table) {
            $table->foreignId('stream_id')->nullable()->constrained()->onDelete('set null');
            $table->string('class_code')->nullable(); // e.g., '11A', '11B'
        });
    }

    public function down()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->dropForeign(['stream_id']);
            $table->dropColumn(['stream_id', 'class_code']);
        });
        
        Schema::dropIfExists('streams');
    }
};