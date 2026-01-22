<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id', // Add this
        'subject_id',
        'assignment_type',
        'assignment_name', // Add this if it exists
        'grade',
        'max_grade',
        'date',
        'comments'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'grade' => 'decimal:2',
        'max_grade' => 'decimal:2',
    ];

    // Accessor to ensure date is always Carbon instance
    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value) : $this->created_at;
    }

    // Accessor for percentage - calculate if not in database
    public function getPercentageAttribute()
    {
        if ($this->max_grade > 0) {
            return ($this->grade / $this->max_grade) * 100;
        }
        
        return 0;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
        public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // REMOVE the classGroup relationship - it doesn't exist in your table
    // public function classGroup(): BelongsTo
    // {
    //     return $this->belongsTo(ClassGroup::class);
    // }

    // Helper method to get letter grade
    public function getLetterGradeAttribute(): string
    {
        return $this->calculateLetterGrade($this->percentage);
    }

    // Helper method to get grade color
    public function getGradeColorAttribute(): string
    {
        if ($this->percentage >= 90) return 'text-green-600';
        if ($this->percentage >= 80) return 'text-blue-600';
        if ($this->percentage >= 70) return 'text-yellow-600';
        if ($this->percentage >= 60) return 'text-orange-600';
        return 'text-red-600';
    }

    private function calculateLetterGrade($percentage): string
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($this->percentage >= 60) return 'D';
        return 'F';
    }
}