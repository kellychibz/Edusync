<?php
// app/Models/AttendanceRecord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'attendance_type_id',
        'attendance_date',
        'notes',
        'is_excused',
        'submitted_at',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'submitted_at' => 'datetime',
        'is_excused' => 'boolean',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attendanceType(): BelongsTo
    {
        return $this->belongsTo(AttendanceType::class);
    }

    // Scopes
    public function scopeForDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }

    public function scopePresent($query)
    {
        return $query->whereHas('attendanceType', function ($q) {
            $q->where('is_present', true);
        });
    }

    public function scopeAbsent($query)
    {
        return $query->whereHas('attendanceType', function ($q) {
            $q->where('is_present', false);
        });
    }
}