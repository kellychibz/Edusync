<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermAssessmentTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'term_assessment_id',
        'final_weight_percentage', // ADD THIS LINE
        'grade_task_id',
        'weight_percentage'
    ];

    protected $casts = [
    'weight_percentage' => 'decimal:2',
    'final_weight_percentage' => 'decimal:2' // ADD THIS LINE
    ];

    public function termAssessment()
    {
        return $this->belongsTo(TermAssessment::class);
    }

    public function gradeTask()
    {
        return $this->belongsTo(GradeTask::class);
    }
}