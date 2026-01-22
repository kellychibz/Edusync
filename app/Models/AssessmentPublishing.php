<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentPublishing extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_group_id',
        'academic_year_id',
        'term_id',
        'published_by',
        'published_at',
        'is_published'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean'
    ];

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}