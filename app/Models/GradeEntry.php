<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'student_id',
        'score',
        'comments',
        'graded_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'graded_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(GradeTask::class, 'task_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function getPercentageAttribute(): ?float
    {
        if ($this->score === null) return null;
        return ($this->score / $this->task->max_score) * 100;
    }

    public function getGradeLetterAttribute(): ?string
    {
        if ($this->percentage === null) return null;
        
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
}