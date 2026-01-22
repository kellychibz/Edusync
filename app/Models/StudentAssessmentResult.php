<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term_assessment_id',
        'score',
        'percentage',
        'grade_letter',
        'comments',
        'graded_at'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'graded_at' => 'datetime'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function termAssessment()
    {
        return $this->belongsTo(TermAssessment::class);
    }
}