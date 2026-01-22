// database/migrations/2024_01_01_000001_create_attendance_types_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Present, Absent, Late, Excused, Sick
            $table->string('code')->unique(); // present, absent, late, excused, sick
            $table->string('color')->default('#6b7280'); // Tailwind colors
            $table->boolean('is_present')->default(false);
            $table->boolean('counts_against_attendance')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_types');
    }
};