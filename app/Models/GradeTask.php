<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'type',
        'max_score',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'max_score' => 'decimal:2',
    ];

    public function classGroup(): BelongsTo
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function gradeEntries(): HasMany
    {
        return $this->hasMany(GradeEntry::class, 'task_id');
    }

    public function getCompletionRateAttribute(): float
    {
        $totalStudents = $this->classGroup->students()->count();
        if ($totalStudents === 0) return 0;
        
        $gradedStudents = $this->gradeEntries()->whereNotNull('score')->count();
        return ($gradedStudents / $totalStudents) * 100;
    }

    // Helper method to get students with their grade entries for this task
    public function studentsWithGrades()
    {
        return $this->classGroup->students()->with(['gradeEntries' => function($query) {
            $query->where('task_id', $this->id);
        }]);
    }
}