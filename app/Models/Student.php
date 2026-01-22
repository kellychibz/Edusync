<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_phone',
        'date_of_birth',
        'admission_number', // Add this
        'photo',
        'gender',
        'address',
        'city',
        'state', 
        'zip_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'medical_conditions',
        'allergies',
        'blood_type',
        'insurance_provider',
        'insurance_policy_number'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'medical_conditions' => 'array',
        'allergies' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classGroups(): BelongsToMany
    {
        return $this->belongsToMany(ClassGroup::class, 'class_group_student')
                    ->withTimestamps();
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function attendanceRecordsForDate($date)
    {
        return $this->hasMany(AttendanceRecord::class)->where('attendance_date', $date);
    }

        // Add this relationship for the new grade management system
    public function gradeEntries()
    {
        return $this->hasMany(GradeEntry::class);
    }

     public function getCurrentClassAttribute()
    {
        return $this->classGroups()->latest()->first();
    }
    public function getFullNameAttribute()
    {
        return $this->user->name;
    }

    public function getEmailAttribute()
    {
        return $this->user->email;
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    // Helper method to get overall average
    public function getOverallAverageAttribute()
    {
        if ($this->grades->isEmpty()) return null;
        
        return $this->grades->avg(function($grade) {
            return $grade->percentage;
        });
    }
    // Helper method to get grades by subject
    public function getGradesBySubject()
    {
        return $this->grades()->with('subject')->get()->groupBy('subject_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}