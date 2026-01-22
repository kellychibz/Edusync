<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Add any missing columns here
            if (!Schema::hasColumn('teachers', 'qualification')) {
                $table->string('qualification')->after('phone');
            }
            if (!Schema::hasColumn('teachers', 'specialization')) {
                $table->string('specialization')->nullable()->after('qualification');
            }
            if (!Schema::hasColumn('teachers', 'years_of_experience')) {
                $table->integer('years_of_experience')->default(0)->after('specialization');
            }
            if (!Schema::hasColumn('teachers', 'hire_date')) {
                $table->date('hire_date')->after('years_of_experience');
            }
            if (!Schema::hasColumn('teachers', 'bio')) {
                $table->text('bio')->nullable()->after('hire_date');
            }
            if (!Schema::hasColumn('teachers', 'office_location')) {
                $table->string('office_location')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('teachers', 'office_hours')) {
                $table->string('office_hours')->nullable()->after('office_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Remove the columns if needed
            $table->dropColumn([
                'qualification', 'specialization', 'years_of_experience',
                'hire_date', 'bio', 'office_location', 'office_hours'
            ]);
        });
    }
};