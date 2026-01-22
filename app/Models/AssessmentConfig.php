<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'subject_id',
        'teacher_id',
        'academic_year_id',
        'ca_percentage',
        'final_exam_percentage',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id'); // Changed to User::class
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}