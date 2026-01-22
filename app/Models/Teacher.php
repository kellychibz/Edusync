<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id', 
        'department_id',
        'phone',
        'address',
        'profile_image', // Add this
        'qualification',
        'specialization',
        'years_of_experience',
        'hire_date',
        'bio',
        'office_location',
        'office_hours',
    ];

    protected $casts = [
        'hire_date' => 'date'
    ];

        // Add this accessor to get the full profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Many-to-many departments
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'teacher_department')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    // Get primary department
    public function primaryDepartment()
    {
        return $this->departments()->wherePivot('is_primary', true)->first();
    }

    public function classGroups() // Classes they teach as class teacher
    {
        return $this->hasMany(ClassGroup::class, 'teacher_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');

    }
    
        // ADD THIS METHOD - Class Subject Assignments relationship
    public function classSubjectAssignments()
    {
        return $this->hasMany(ClassSubject::class, 'teacher_id');
    }

    // Add this method to the Teacher model
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }


    // Calculate workload (total periods per week)
    public function getWorkloadAttribute()
    {
        return $this->classSubjectAssignments()->sum('periods_per_week');
    }

    public function isDepartmentHead()
    {
        return $this->department && $this->department->head_teacher_id === $this->id;
    }

    public function gradeTasks()
    {
        return $this->hasMany(GradeTask::class);
    }


}