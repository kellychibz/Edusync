<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable(false)->change();
        });
    }
};
