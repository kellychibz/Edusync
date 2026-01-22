<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'subject_id',
        'teacher_id',
        'term_id',
        'title',
        'description',
        'total_weight_percentage', // ADD THIS LINE
        'ca_allocation_type',      // ADD THIS LINE
        'is_final_exam',           // ADD THIS LINE
        'max_score',
        'due_date',
        'is_published'
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'total_weight_percentage' => 'decimal:2', // ADD THIS LINE
        'is_final_exam' => 'boolean',             // ADD THIS LINE
        'due_date' => 'date',
        'is_published' => 'boolean'
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

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function tasks()
    {
        return $this->hasMany(TermAssessmentTask::class);
    }

    public function studentResults()
    {
        return $this->hasMany(StudentAssessmentResult::class);
    }
}