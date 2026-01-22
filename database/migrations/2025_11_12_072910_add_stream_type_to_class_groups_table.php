<?php
// database/migrations/2025_11_11_140005_add_stream_type_to_class_groups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->enum('stream_type', [
                'science', 'arts', 'technical', 'commercial', 'general'
            ])->default('general')->after('grade_level_id');
        });
    }

    public function down()
    {
        Schema::table('class_groups', function (Blueprint $table) {
            $table->dropColumn('stream_type');
        });
    }
};