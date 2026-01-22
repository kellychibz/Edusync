<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('term_assessments', function (Blueprint $table) {
            // Add total weight percentage (e.g., 20% of final grade)
            $table->decimal('total_weight_percentage', 5, 2)->default(0)->after('max_score');
            
            // Add CA allocation type (term1_ca, term2_ca, final_exam)
            $table->enum('ca_allocation_type', ['term1_ca', 'term2_ca', 'final_exam'])
                  ->default('term1_ca')
                  ->after('total_weight_percentage');
            
            // Add is_final_exam flag
            $table->boolean('is_final_exam')->default(false)->after('ca_allocation_type');
        });

        Schema::table('term_assessment_tasks', function (Blueprint $table) {
            // Add final weight percentage (weight_percentage × total_weight_percentage / 100)
            $table->decimal('final_weight_percentage', 5, 2)->default(0)->after('weight_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_assessments', function (Blueprint $table) {
            $table->dropColumn(['total_weight_percentage', 'ca_allocation_type', 'is_final_exam']);
        });

        Schema::table('term_assessment_tasks', function (Blueprint $table) {
            $table->dropColumn('final_weight_percentage');
        });
    }
};