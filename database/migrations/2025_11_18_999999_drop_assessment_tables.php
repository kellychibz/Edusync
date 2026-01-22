<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Drop tables in reverse order of dependencies
        Schema::dropIfExists('assessment_publishing');
        Schema::dropIfExists('student_assessment_results');
        Schema::dropIfExists('term_assessment_tasks');
        Schema::dropIfExists('term_assessments');
        Schema::dropIfExists('assessment_configs');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('academic_years');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // This is a one-way migration
    }
};