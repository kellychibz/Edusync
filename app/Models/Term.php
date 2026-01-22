<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'term_number',
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function termAssessments()
    {
        return $this->hasMany(TermAssessment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByNumber($query, $termNumber)
    {
        return $query->where('term_number', $termNumber);
    }
}