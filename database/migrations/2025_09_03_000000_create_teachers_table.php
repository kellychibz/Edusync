<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('qualification');
            $table->string('specialization')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->date('hire_date');
            $table->text('bio')->nullable();
            $table->string('office_location')->nullable();
            $table->string('office_hours')->nullable();
            $table->string('profile_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};