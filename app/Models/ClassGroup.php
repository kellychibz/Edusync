<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'section',
        'subject',
        'room_number',
        'teacher_id',
        'grade_level_id', 
        'stream_type',
        'start_time',
        'end_time',
        'teacher_id',
        'academic_year',
        'semester',
        'is_active',
        'capacity',
        'description',
        'class_code',
        'stream_id'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'academic_year' => 'integer',
        'is_active' => 'boolean'
        
    ];

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }


    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_group_student')
                    ->withTimestamps();
    }

    // Add this method to the ClassGroup model
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
                    ->using(ClassSubject::class)
                    ->withPivot(['teacher_id', 'periods_per_week', 'schedule', 'room_number'])
                    ->withTimestamps();
    }


    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    // If you want a direct subject relationship from the subject field
    public function mainSubject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Helper method to get full class name
    // Helper methods
    public function getFullNameAttribute()
    {
        $grade = $this->gradeLevel ? $this->gradeLevel->name : '';
        $stream = $this->stream ? $this->stream->name : ($this->stream_type ? ucfirst($this->stream_type) : '');
        
        return trim("{$grade} {$this->class_code} - {$stream}");
    }

    // Helper method to get current enrollment count
    public function getEnrollmentCountAttribute()
    {
        return $this->students()->count();
    }

    // Check if class has available spots
    public function hasAvailableSpots()
    {
        return $this->enrollment_count < $this->capacity;
    }

        public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    public function getSubjectCountAttribute()
    {
        return $this->subjects()->count();
    }

    public function getFormTeacherAttribute()
    {
        return $this->teacher ? $this->teacher->user->name : 'Not Assigned';
    }
}