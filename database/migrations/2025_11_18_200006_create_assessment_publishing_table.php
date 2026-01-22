<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assessment_publishing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('term_id')->constrained()->onDelete('cascade');
            $table->foreignId('published_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            
            $table->unique(['class_group_id', 'academic_year_id', 'term_id'], 'publish_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessment_publishing');
    }
};