<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Personal information
            $table->string('photo')->nullable()->after('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('photo');
            
            // Address information
            $table->string('address')->nullable()->after('gender');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('zip_code')->nullable()->after('state');
            $table->string('country')->default('Kenya')->after('zip_code');
            
            // Emergency contact information
            $table->string('emergency_contact_name')->nullable()->after('country');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            
            // Medical information
            $table->text('medical_conditions')->nullable()->after('emergency_contact_relationship');
            $table->text('allergies')->nullable()->after('medical_conditions');
            $table->string('blood_type')->nullable()->after('allergies');
            $table->string('insurance_provider')->nullable()->after('blood_type');
            $table->string('insurance_policy_number')->nullable()->after('insurance_provider');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'photo', 'gender', 'address', 'city', 'state', 'zip_code', 'country',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
                'medical_conditions', 'allergies', 'blood_type', 'insurance_provider', 'insurance_policy_number'
            ]);
        });
    }
};